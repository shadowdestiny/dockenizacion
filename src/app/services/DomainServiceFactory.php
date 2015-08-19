<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\components\EmTranslationAdapter;
use EuroMillions\components\MandrillWrapper;
use EuroMillions\components\PhpassWrapper;
use EuroMillions\interfaces\IAuthStorageStrategy;
use EuroMillions\interfaces\ICurrencyApi;
use EuroMillions\interfaces\IMailServiceApi;
use EuroMillions\interfaces\IPasswordHasher;
use EuroMillions\interfaces\IUrlManager;
use EuroMillions\interfaces\IUsersPreferencesStorageStrategy;
use EuroMillions\interfaces\ILanguageStrategy;
use EuroMillions\repositories\LanguageRepository;
use EuroMillions\repositories\UserRepository;
use EuroMillions\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\external_apis\RedisCurrencyApiCache;
use EuroMillions\services\external_apis\YahooCurrencyApi;
use EuroMillions\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\services\preferences_strategies\WebUserPreferencesStorageStrategy;
use Phalcon\Di;
use Phalcon\DiInterface;

class DomainServiceFactory
{
    const ENTITIES_NS = 'EuroMillions\entities\\';
    private $di;
    private $entityManager;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;
        $this->entityManager = $di->get('entityManager');
    }

    public function getLotteriesDataService(EntityManager $entityManager = null, LotteryApisFactory $lotteryApisFactory = null)
    {
        if (!$entityManager) $entityManager = $this->di->get('entityManager');
        if (!$lotteryApisFactory) $lotteryApisFactory = new LotteryApisFactory();
        return new LotteriesDataService($entityManager, $lotteryApisFactory);
    }

    public function getLanguageService(ILanguageStrategy $languageStrategy = null, LanguageRepository $languageRepository = null, EmTranslationAdapter $translationAdapter = null)
    {
        if (!$languageStrategy) $languageStrategy = new WebLanguageStrategy($this->di->get('session'), $this->di->get('request'));
        if (!$languageRepository) $languageRepository = $this->getRepository('Language');
        if (!$translationAdapter) {
            $translationAdapter = new EmTranslationAdapter($languageStrategy->get(), $this->getRepository('TranslationDetail'));
        }
        return new LanguageService($languageStrategy, $languageRepository, $translationAdapter);
    }

    public function getCurrencyService(ICurrencyApi $currencyApi = null, LanguageService $languageService = null)
    {
        if (!$currencyApi) $currencyApi = new YahooCurrencyApi(new RedisCurrencyApiCache($this->di->get('redisCache')));
        if (!$languageService) $languageService = $this->di->get('language');
        return new CurrencyService($currencyApi, $languageService);
    }

    /**
     * @param UserRepository|null $userRepository
     * @param CurrencyService|null $currencyService
     * @param IUsersPreferencesStorageStrategy $preferencesStrategy
     * @return UserService
     */
    public function getUserService(UserRepository $userRepository = null, CurrencyService $currencyService = null, IUsersPreferencesStorageStrategy $preferencesStrategy = null)
    {
        if (!$userRepository) $userRepository = $this->getRepository('User');
        if (!$currencyService) $currencyService = $this->getCurrencyService();
        if (!$preferencesStrategy) $preferencesStrategy = new WebUserPreferencesStorageStrategy($this->di->get('session'), $this->di->get('cookies'));
        return new UserService($userRepository, $currencyService, $preferencesStrategy);
    }

    /**
     * @param IPasswordHasher $passwordHasher
     * @param IAuthStorageStrategy $storageStrategy
     * @return AuthService
     */
    public function getAuthService(IPasswordHasher $passwordHasher = null, IAuthStorageStrategy $storageStrategy = null, IUrlManager $urlManager= null)
    {
        if (!$storageStrategy) $storageStrategy = new WebAuthStorageStrategy($this->di->get('session'), $this->di->get('cookies'));
        if (!$passwordHasher) $passwordHasher = new PhpassWrapper();
        if (!$urlManager) $urlManager = $this->di->get('url');
        return new AuthService($this->entityManager, $passwordHasher, $storageStrategy, $urlManager);
    }

    /**
     * @return GeoService
     */
    public function getGeoService()
    {
        return new GeoService();
    }

    public function getEmailService(IMailServiceApi $mailServiceApi = null, AuthService $authService = null, $config = null)
    {
        if (!$config) $config = $this->di->get('globalConfig')['mail'];
        $api_key = $config['mandrill_api_key'];
        if (!$mailServiceApi) $mailServiceApi = new MandrillWrapper($api_key);
        if (!$authService) $authService = $this->getAuthService();
        return new EmailService($mailServiceApi, $authService, $config);
    }

    private function getRepository($entity)
    {
        return $this->entityManager->getRepository(self::ENTITIES_NS . $entity);
    }
}