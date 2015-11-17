<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\interfaces\IUsersPreferencesStorageStrategy;
use EuroMillions\web\interfaces\ILanguageStrategy;
use EuroMillions\web\repositories\LanguageRepository;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentProvider;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\services\preferences_strategies\WebUserPreferencesStorageStrategy;
use Phalcon\Di;
use Phalcon\DiInterface;
use EuroMillions\web\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\web\components\PhpassWrapper;
use EuroMillions\web\interfaces\IAuthStorageStrategy;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\shareconfig\interfaces\IUrlManager;



class DomainServiceFactory
{
    const ENTITIES_NS = 'EuroMillions\web\entities\\';
    private $entityManager;
    private $serviceFactory;

    public function __construct(DiInterface $di, ServiceFactory $serviceFactory)
    {
        $this->entityManager = $di->get('entityManager');
        $this->serviceFactory = $serviceFactory;
    }

    public function getCreditCardPaymentService(ICardPaymentProvider $provider = null)
    {
        if (!$provider) $provider = new FakeCardPaymentProvider();
        return new CreditCardPaymentService($provider);
    }

    public function getLotteriesDataService(EntityManager $entityManager = null, LotteryApisFactory $lotteryApisFactory = null)
    {
        if (!$entityManager) $entityManager = $this->entityManager;
        if (!$lotteryApisFactory) $lotteryApisFactory = new LotteryApisFactory();
        return new LotteriesDataService($entityManager, $lotteryApisFactory);
    }

    public function getLanguageService(ILanguageStrategy $languageStrategy = null, LanguageRepository $languageRepository = null, EmTranslationAdapter $translationAdapter = null)
    {
        if (!$languageStrategy) $languageStrategy = new WebLanguageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('request'));
        if (!$languageRepository) $languageRepository = $this->getRepository('Language');
        if (!$translationAdapter) {
            $translationAdapter = new EmTranslationAdapter($languageStrategy->get(), $this->getRepository('TranslationDetail'));
        }
        return new LanguageService($languageStrategy, $languageRepository, $translationAdapter);
    }

    /**
     * @param CurrencyService|null $currencyService
     * @param EmailService $emailService
     * @param PaymentProviderService $paymentProviderService
     * @return UserService
     */
    public function getUserService(CurrencyService $currencyService = null,
                                   EmailService $emailService = null,
                                   PaymentProviderService $paymentProviderService = null
                                   )
    {
        if (!$currencyService) $currencyService = $this->serviceFactory->getCurrencyService();
        if (!$emailService) $emailService = $this->serviceFactory->getEmailService();
        if (!$paymentProviderService) $paymentProviderService = new PaymentProviderService();
        return new UserService($currencyService, $emailService, $paymentProviderService, $this->entityManager);
    }

    public function getUserPreferencesService(CurrencyService $currencyService = null,
                                              IUsersPreferencesStorageStrategy $preferencesStrategy = null)
    {
        if (!$currencyService) $currencyService = $this->serviceFactory->getCurrencyService();
        if (!$preferencesStrategy) $preferencesStrategy = new WebUserPreferencesStorageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('cookies'));
        return new UserPreferencesService($currencyService, $preferencesStrategy);
    }

    /**
     * @param IPasswordHasher $passwordHasher
     * @param IAuthStorageStrategy $storageStrategy
     * @param IUrlManager $urlManager
     * @param LogService $logService
     * @param EmailService $emailService
     * @return AuthService
     */
    public function getAuthService(IPasswordHasher $passwordHasher = null, IAuthStorageStrategy $storageStrategy = null, IUrlManager $urlManager = null, LogService $logService = null, EmailService $emailService = null, UserService $userService = null)
    {
        if (!$storageStrategy) $storageStrategy = new WebAuthStorageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('cookies'));
        if (!$passwordHasher) $passwordHasher = new PhpassWrapper();
        if (!$urlManager) $urlManager = $this->serviceFactory->getDI()->get('url');
        if (!$logService) $logService = $this->serviceFactory->getLogService();
        if (!$emailService) $emailService = $this->serviceFactory->getEmailService();
        if (!$userService) $userService = $this->getUserService();
        return new AuthService($this->entityManager, $passwordHasher, $storageStrategy, $urlManager, $logService, $emailService, $userService);
    }

    public function getPlayService(LotteriesDataService $lotteriesDataService = null, IPlayStorageStrategy $playStorageStrategy = null)
    {
        if (!$lotteriesDataService) $lotteriesDataService = new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        if (!$playStorageStrategy)  $playStorageStrategy = new RedisPlayStorageStrategy($this->serviceFactory->getDI()->get('redisCache'));
         return new PlayService($this->entityManager, $lotteriesDataService, $playStorageStrategy);
    }

    public function getPriceCheckoutService(LotteriesDataService $lotteriesDataService = null)
    {
        if (!$lotteriesDataService) $lotteriesDataService = new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        return new PriceCheckoutService($this->entityManager, $lotteriesDataService);
    }

    private function getRepository($entity)
    {
        return $this->entityManager->getRepository(self::ENTITIES_NS . $entity);
    }

    public function getServiceFactory()
    {
        return $this->serviceFactory;
    }

}