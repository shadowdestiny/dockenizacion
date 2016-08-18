<?php
namespace EuroMillions\web\services\factories;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\repositories\LanguageRepository;
use EuroMillions\web\repositories\TranslationDetailRepository;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\BetService;
use EuroMillions\web\services\CartService;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\external_apis\CurrencyConversion\CurrencyLayerApi;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\services\external_apis\CurrencyConversion\RedisCurrencyApiCache;
use EuroMillions\web\services\LanguageService;
use EuroMillions\web\services\LoggedAuthServiceNullStrategy;
use EuroMillions\web\services\LoggedAuthServiceStrategy;
use EuroMillions\web\services\LoggedUserServiceStrategy;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PaymentProviderService;
use EuroMillions\web\services\play_strategies\RedisOrderStorageStrategy;
use EuroMillions\web\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\services\preferences_strategies\WebUserPreferencesStorageStrategy;
use EuroMillions\web\services\PrizeCheckoutService;
use EuroMillions\web\services\TransactionDetailService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\UserNotificationsService;
use EuroMillions\web\services\UserPreferencesService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\services\WalletService;
use Phalcon\Di;
use Phalcon\DiInterface;
use EuroMillions\web\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\web\components\PhpassWrapper;
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

    public function getWalletService()
    {
        return new WalletService($this->entityManager,
                                 $this->getCurrencyConversionService(),
                                 $this->getTransactionService()
            );
    }

    public function getLotteriesDataService()
    {
        return new LotteriesDataService(
            $this->entityManager, new LotteryApisFactory()
        );
    }

    public function getLotteryService()
    {
        return new LotteryService($this->entityManager,
                                  $this->getLotteriesDataService(),
                                  $this->getUserService(),
                                  $this->getBetService(),
                                  $this->getServiceFactory()->getEmailService(),
                                  $this->getUserNotificationsService(),
                                  $this->getWalletService()
            );
    }

    public function getLanguageService()
    {
        /** @var LanguageRepository $languageRepository */
        $languageRepository = $this->getRepository('Language');
        /** @var TranslationDetailRepository $translation_detail_repository */
        $translation_detail_repository = $this->getRepository('TranslationDetail');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($this->serviceFactory->getDI()->get('session'), $this->serviceFactory->getDI()->get('request')))->get(), $translation_detail_repository);
        return new LanguageService(
            new WebLanguageStrategy(
                $this->serviceFactory->getDI()->get('session'),
                $this->serviceFactory->getDI()->get('request')
            ),
            $languageRepository,
            $translationAdapter
        );
    }

    public function getUserService()
    {
        return new LoggedUserServiceStrategy(
            $this->getCurrencyConversionService(),
            $this->serviceFactory->getEmailService(),
            new PaymentProviderService(),
            $this->getWalletService(),
            $this->entityManager,
            $this->serviceFactory->getLogService()
        );
    }

    public function getUserPreferencesService()
    {
        return new UserPreferencesService(
            $this->getCurrencyConversionService(),
            new WebUserPreferencesStorageStrategy(
                $this->serviceFactory->getDI()->get('session'),
                $this->serviceFactory->getDI()->get('cookies')
            )
        );
    }

    public function getAuthService()
    {
        return new LoggedAuthServiceStrategy(
            $this->entityManager,
            new PhpassWrapper(),
            new WebAuthStorageStrategy(
                $this->serviceFactory->getDI()->get('session'),
                $this->serviceFactory->getDI()->get('cookies')),
            $this->serviceFactory->getDI()->get('url'),
            $this->serviceFactory->getLogService(),
            $this->serviceFactory->getEmailService(),
            $this->getUserService());
    }

    public function getPlayService()
    {
        return new PlayService(
            $this->entityManager,
            $this->getLotteryService(),
            new RedisPlayStorageStrategy(
                $this->serviceFactory->getDI()->get('redisCache')
            ),
            new RedisOrderStorageStrategy(
                $this->serviceFactory->getDI()->get('redisCache')
            ),
            $this->getCartService(),
            $this->getWalletService(),
            $this->serviceFactory->getDI()->get('paymentProviderFactory'),
            $this->getBetService(),
            $this->serviceFactory->getEmailService()
        );
    }

    public function getBetService()
    {
        return new BetService(
            $this->entityManager
        );
    }

    public function getCartService()
    {
        return new CartService(
            $this->entityManager,
            new RedisOrderStorageStrategy($this->serviceFactory->getDI()->get('redisCache'))
        );
    }

    public function getPrizeCheckoutService()
    {
        return new PrizeCheckoutService(
            $this->entityManager,
            $this->getCurrencyConversionService(),
            $this->getUserService(),
            $this->serviceFactory->getEmailService(),
            $this->getTransactionService()
        );
    }

    public function getCurrencyService()
    {
        return new CurrencyService($this->entityManager);
    }

    public function getTransactionService()
    {
        return new TransactionService($this->entityManager, $this->getCurrencyConversionService());
    }

    public function getTransactionDetailService()
    {
        return new TransactionDetailService($this->entityManager,$this->getCurrencyConversionService());
    }

    public function getCurrencyConversionService()
    {
        $redis_cache = $this->serviceFactory->getDI()->get('redisCache');
        return new CurrencyConversionService(
            new CurrencyLayerApi(
                '802187a0471a2c43f41b1ff3f777d26c',
                new Curl(),
                new RedisCurrencyApiCache($redis_cache)
            )
        );
    }

    public function getUserNotificationsService()
    {
        return new UserNotificationsService(
            $this->entityManager,
            $this->getUserService()
        );
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