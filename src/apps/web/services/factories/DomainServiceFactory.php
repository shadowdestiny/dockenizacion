<?php
namespace EuroMillions\web\services\factories;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\interfaces\IUsersPreferencesStorageStrategy;
use EuroMillions\web\interfaces\ILanguageStrategy;
use EuroMillions\web\interfaces\IXchangeGetter;
use EuroMillions\web\repositories\LanguageRepository;
use EuroMillions\web\repositories\TranslationDetailRepository;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\BetService;
use EuroMillions\web\services\CartService;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\external_apis\CurrencyConversion\CurrencyLayerApi;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\services\external_apis\CurrencyConversion\RedisCurrencyApiCache;
use EuroMillions\web\services\LanguageService;
use EuroMillions\web\services\LogService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\PaymentProviderService;
use EuroMillions\web\services\play_strategies\RedisOrderStorageStrategy;
use EuroMillions\web\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\services\preferences_strategies\WebUserPreferencesStorageStrategy;
use EuroMillions\web\services\PriceCheckoutService;
use EuroMillions\web\services\UserPreferencesService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\services\WalletService;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Di;
use Phalcon\DiInterface;
use EuroMillions\web\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\web\components\PhpassWrapper;
use EuroMillions\web\interfaces\IAuthStorageStrategy;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\shared\config\interfaces\IUrlManager;
use Phalcon\Http\Client\Provider\Curl;


class DomainServiceFactory
{
    const ENTITIES_NS = 'EuroMillions\web\entities\\';
    /** @var EntityManager */
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
        /** @var LanguageRepository $languageRepository */
        $languageRepository = $languageRepository ?: $this->getRepository('Language');
        /** @var TranslationDetailRepository $translation_detail_repository */
        $translation_detail_repository = $this->getRepository('TranslationDetail');
        $translationAdapter = $translationAdapter ?: new EmTranslationAdapter($languageStrategy->get(), $translation_detail_repository);
        return new LanguageService($languageStrategy, $languageRepository, $translationAdapter);
    }

    /**
     * @param CurrencyConversionService|null $currencyConversionService
     * @param EmailService|null $emailService
     * @param PaymentProviderService|null $paymentProviderService
     * @return UserService
     */
    public function getUserService(CurrencyConversionService $currencyConversionService = null,
                                   EmailService $emailService = null,
                                   PaymentProviderService $paymentProviderService = null
    )
    {
        $currencyConversionService = $currencyConversionService ?: $this->getCurrencyConversionService();
        $emailService = $emailService ?: $this->serviceFactory->getEmailService();
        $paymentProviderService = $paymentProviderService ?: new PaymentProviderService();
        return new UserService($currencyConversionService, $emailService, $paymentProviderService, $this->entityManager);
    }

    public function getUserPreferencesService(CurrencyConversionService $currencyConversionService = null,
                                              IUsersPreferencesStorageStrategy $preferencesStrategy = null)
    {
        $currencyConversionService = $currencyConversionService ?: $this->getCurrencyConversionService();
        $preferencesStrategy = $preferencesStrategy ?: new WebUserPreferencesStorageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('cookies'));
        return new UserPreferencesService($currencyConversionService, $preferencesStrategy);
    }

    /**
     * @param IPasswordHasher|null $passwordHasher
     * @param IAuthStorageStrategy|null $storageStrategy
     * @param IUrlManager|null $urlManager
     * @param LogService|null $logService
     * @param EmailService|null $emailService
     * @param UserService|null $userService
     * @return AuthService
     */
    public function getAuthService(IPasswordHasher $passwordHasher = null, IAuthStorageStrategy $storageStrategy = null, IUrlManager $urlManager = null, LogService $logService = null, EmailService $emailService = null, UserService $userService = null)
    {
        $storageStrategy = $storageStrategy ?: new WebAuthStorageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('cookies'));
        $passwordHasher = $passwordHasher ?: new PhpassWrapper();
        $urlManager = $urlManager ? $urlManager : $this->serviceFactory->getDI()->get('url');
        $logService = $logService ? $logService : $this->serviceFactory->getLogService();
        $emailService = $emailService ? $emailService : $this->serviceFactory->getEmailService();
        $userService = $userService ? $userService : $this->getUserService();
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
        return new PlayService($this->entityManager, $lotteriesDataService, $playStorageStrategy, $orderStorageStrategy, $cartService, $walletService, $payXpertCardPaymentStrategy, $betService);
    }

    public function getBetService(LotteriesDataService $lotteriesDataService = null)
    {
        $lotteriesDataService = $lotteriesDataService ?: new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        return new BetService($this->entityManager, $lotteriesDataService);
    }

    public function getCartService(IPlayStorageStrategy $orderStorageStrategy = null)
    {
        $orderStorageStrategy = $orderStorageStrategy ?: new RedisOrderStorageStrategy($this->serviceFactory->getDI()->get('redisCache'));

        return new CartService($this->entityManager, $orderStorageStrategy);
    }

    public function getPriceCheckoutService(LotteriesDataService $lotteriesDataService = null,
                                            CurrencyConversionService $currencyConversionService = null,
                                            UserService $userService = null,
                                            EmailService $emailService = null)
    {
        $lotteriesDataService = $lotteriesDataService ?: new LotteriesDataService($this->entityManager, new LotteryApisFactory());
        $currencyConversionService = $currencyConversionService ?: $this->getCurrencyConversionService();
        $userService = $userService ?: $this->getUserService();
        $emailService = $emailService ?: $this->serviceFactory->getEmailService();
        return new PriceCheckoutService($this->entityManager, $lotteriesDataService, $currencyConversionService, $userService, $emailService);
    }

    public function getCurrencyService()
    {
        return new CurrencyService($this->entityManager);
    }

    public function getCurrencyConversionService(IXchangeGetter $api = null)
    {
        /** @var Redis $redis_cache */
        $redis_cache = $this->serviceFactory->getDI()->get('redisCache');
        $api = $api ?:
            new CurrencyLayerApi(
                '802187a0471a2c43f41b1ff3f777d26c',
                new Curl(),
                new RedisCurrencyApiCache($redis_cache)
        );
        return new CurrencyConversionService($api);
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