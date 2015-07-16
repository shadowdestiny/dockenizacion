<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\components\EmTranslationAdapter;
use EuroMillions\interfaces\ICurrencyApi;
use EuroMillions\interfaces\ILanguageStrategy;
use EuroMillions\repositories\LanguageRepository;
use EuroMillions\repositories\UserRepository;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\external_apis\RedisCurrencyApiCache;
use EuroMillions\services\external_apis\YahooCurrencyApi;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di;

class DomainServiceFactory
{
    const ENTITIES_NS = 'EuroMillions\entities\\';
    private $di;
    private $entityManager;

    public function __construct(Di $di)
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

    public function getLanguageService(ILanguageStrategy $languageStrategy, LanguageRepository $languageRepository = null, EmTranslationAdapter $translationAdapter = null)
    {
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
     * @return UserService
     */
    public function getUserService(UserRepository $userRepository = null, CurrencyService $currencyService = null)
    {
        if (!$userRepository) $userRepository = $this->getRepository('User');
        if (!$currencyService) $currencyService = $this->getCurrencyService();
        return new UserService($userRepository, $currencyService);
    }

    private function getRepository($entity)
    {
        return $this->entityManager->getRepository(self::ENTITIES_NS.$entity);
    }
}