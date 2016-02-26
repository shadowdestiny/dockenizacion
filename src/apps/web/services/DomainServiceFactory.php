<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\ICurrencyApi;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\interfaces\IUsersPreferencesStorageStrategy;
use EuroMillions\web\interfaces\ILanguageStrategy;
use EuroMillions\web\repositories\LanguageRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\card_payment_providers\PayXpertCardPaymentStrategy;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\services\external_apis\RedisCurrencyApiCache;
use EuroMillions\web\services\external_apis\YahooCurrencyApi;
use EuroMillions\web\services\play_strategies\RedisOrderStorageStrategy;
use EuroMillions\web\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\services\preferences_strategies\WebUserPreferencesStorageStrategy;
use Phalcon\Di;
use Phalcon\DiInterface;
use EuroMillions\web\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\web\components\PhpassWrapper;
use EuroMillions\web\interfaces\IAuthStorageStrategy;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\shared\config\interfaces\IUrlManager;



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

    public function getWalletService(EntityManager $entityManager = null)
    {
        $entityManager = $entityManager ?: $this->entityManager;
        return new WalletService($entityManager);
    }

    public function getLotteriesDataService(EntityManager $entityManager = null, LotteryApisFactory $lotteryApisFactory = null)
    {
        $entityManager = $entityManager ?: $this->entityManager;
        $lotteryApisFactory = $lotteryApisFactory ?: new LotteryApisFactory();
        return new LotteriesDataService($entityManager, $lotteryApisFactory);
    }

    public function getLanguageService(ILanguageStrategy $languageStrategy = null, LanguageRepository $languageRepository = null, EmTranslationAdapter $translationAdapter = null)
    {
        $languageStrategy = $languageStrategy ?: new WebLanguageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('request'));
        $languageRepository = $languageRepository ?:$this->getRepository('Language');
        $translationAdapter = $translationAdapter ?: new EmTranslationAdapter($languageStrategy->get(), $this->getRepository('TranslationDetail'));
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
        $currencyService = $currencyService ?: $this->getCurrencyService();
        $emailService = $emailService ?: $this->serviceFactory->getEmailService();
        $paymentProviderService = $paymentProviderService ?: new PaymentProviderService();
        return new UserService($currencyService, $emailService, $paymentProviderService, $this->entityManager);
    }

    public function getUserPreferencesService(CurrencyService $currencyService = null,
                                              IUsersPreferencesStorageStrategy $preferencesStrategy = null)
    {
        $currencyService = $currencyService ?: $this->getCurrencyService();
        $preferencesStrategy = $preferencesStrategy ?: new WebUserPreferencesStorageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('cookies'));
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
        $storageStrategy = $storageStrategy ?: new WebAuthStorageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('cookies'));
        $passwordHasher = $passwordHasher ?: new PhpassWrapper();
        $urlManager = $urlManager ?: $this->serviceFactory->getDI()->get('url');
        $logService = $logService ?: $this->serviceFactory->getLogService();
        $emailService = $emailService ?: $this->serviceFactory->getEmailService();
        $userService = $userService ?: $this->getUserService();
        return new AuthService($this->entityManager, $passwordHasher, $storageStrategy, $urlManager, $logService, $emailService, $userService);
    }

    public function getPlayService(LotteriesDataService $lotteriesDataService = null,
                                   IPlayStorageStrategy $playStorageStrategy = null,
                                   IPlayStorageStrategy $orderStorageStrategy = null,
                                   CartService $cartService = null,
                                   WalletService $walletService = null,
                                   ICardPaymentProvider $payXpertCardPaymentStrategy = null,
                                   BetService $betService = null)
    {
        $lotteriesDataService = $lotteriesDataService ?: new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        $playStorageStrategy = $playStorageStrategy ?: new RedisPlayStorageStrategy($this->serviceFactory->getDI()->get('redisCache'));
        $orderStorageStrategy = $orderStorageStrategy ?: new RedisOrderStorageStrategy($this->serviceFactory->getDI()->get('redisCache'));
        $cartService = $cartService ?: new CartService($this->entityManager, $orderStorageStrategy);
        $walletService = $walletService ?: new WalletService($this->entityManager, $this->getCurrencyService());
        $payXpertCardPaymentStrategy = $payXpertCardPaymentStrategy ?: $this->serviceFactory->getDI()->get('paymentProviderFactory');
        $betService = $betService ?: new BetService($this->entityManager, $this->getLotteriesDataService());
        return new PlayService($this->entityManager, $lotteriesDataService, $playStorageStrategy, $orderStorageStrategy, $cartService, $walletService,$payXpertCardPaymentStrategy, $betService);
    }

    public function getBetService(LotteriesDataService $lotteriesDataService = null)
    {
        $lotteriesDataService = $lotteriesDataService ?: new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        return new BetService($this->entityManager, $lotteriesDataService);
    }

    public function getCartService( IPlayStorageStrategy $orderStorageStrategy = null )
    {
        $orderStorageStrategy = $orderStorageStrategy ?: new RedisOrderStorageStrategy($this->serviceFactory->getDI()->get('redisCache'));

        return new CartService($this->entityManager, $orderStorageStrategy);
    }

    public function getPriceCheckoutService(LotteriesDataService $lotteriesDataService = null,
                                            CurrencyService $currencyService = null,
                                            UserService $userService = null,
                                            EmailService $emailService = null)
    {
        $lotteriesDataService = $lotteriesDataService ?: new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        $currencyService = $currencyService ?: $this->getCurrencyService();
        $userService = $userService ?: $this->getUserService();
        $emailService = $emailService ?: $this->serviceFactory->getEmailService();
        return new PriceCheckoutService($this->entityManager, $lotteriesDataService,$currencyService,$userService,$emailService);
    }

    public function getCurrencyService(ICurrencyApi $currencyApi = null)
    {
        $currencyApi = $currencyApi ?: new YahooCurrencyApi(new RedisCurrencyApiCache($this->serviceFactory->getDI()->get('redisCache')));
        return new CurrencyService($currencyApi, $this->entityManager);
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