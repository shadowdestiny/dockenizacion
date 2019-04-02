<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\services\SiteConfigService;
use EuroMillions\shared\vo\RedisOrderKey;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\OrderPowerBall;
use EuroMillions\web\vo\TransactionId;
use Money\Currency;
use Money\Money;

class CartService
{

    private $entityManager;

    private $orderStorageStrategy;

    private $userRepository;

    /** @var LotteryRepository $lotteryRepository */
    private $lotteryRepository;

    /** @var SiteConfigService $siteConfigService */
    private $siteConfigService;

    /** @var PlayConfigRepository $playConfigRepository */
    private $playConfigRepository;

    /** @var PlayService $playService */
    protected $playService;

    public function __construct(EntityManager $entityManager,
                                IPlayStorageStrategy $orderStorageStrategy,
                                SiteConfigService $siteConfigService,
                                WalletService $walletService)
    {
        $this->entityManager = $entityManager;
        $this->orderStorageStrategy = $orderStorageStrategy;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->lotteryRepository = $entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->walletService = $walletService;
        $this->siteConfigService = $siteConfigService;
    }

    public function store(Order $order)
    {
        $user_id = $order->getPlayConfig()[0]->getUser()->getId();
        if (null !== $user_id) {
            /** @var ActionResult $result */
            $result = $this->orderStorageStrategy->save($order->toJsonData(), RedisOrderKey::create($user_id,$order->getLottery()->getId())->key());
            if ($result->success()) {
                return $result;
            } else {
                return new ActionResult(false);
            }
        }
        return new ActionResult(false);
    }

    public function get($user_id, $lotteryName,$withWallet = false)
    {
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            /** @var ActionResult $result */
            $result = $this->orderStorageStrategy->findByKey(RedisOrderKey::create($user_id,$lottery->getId())->key());
            if ($result->success()) {
                $json = json_decode($result->returnValues());

                if (NULL == $json) {
                    return new ActionResult(false);
                }
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                if (null !== $user) {
                    $bets = [];
                    foreach ($json->play_config as $bet) {
                        $playConfig = new PlayConfig();
                        $playConfig->setLottery($lottery);
                        $playConfig->formToEntity($user, $bet, $bet->euromillions_line);
                        $bets[] = $playConfig;
                    }
                    $fee = $this->siteConfigService->getFee();
                    $fee_limit = $this->siteConfigService->getFeeToLimitValue();

                    $arrayFromjson = json_decode($result->getValues(), TRUE); //TODO: refactor.

                    //TODO: check $result->getValues to var
                    $order = OrderFactory::create(
                        $bets,
                        $lottery->getSingleBetPrice(),
                        $fee, $fee_limit,
                        new Discount($bets[0]->getFrequency(), $this->playConfigRepository->retrieveEuromillionsBundlePrice()),
                        $lottery,
                        $result->getValues(),
                        $withWallet,
                        new TransactionId($arrayFromjson['transactionId'])
                    );
                    if (null !== $order) {
                        return new ActionResult(true, $order);
                    }
                }
            } else {
                return new ActionResult(false, 'Order doesn\'t exist');
            }
        } catch (\RedisException $r) {
            return new ActionResult(false, $r->getMessage());
        }
        return new ActionResult(false);
    }

    public function checkout($event,$component,Order $order)
    {

    }


    public function amountCalculateWithCreditCardAndBalance(Money $orderAmount, Money $walletAmount, $isWallet)
    {
        if($isWallet == 'false')
        {
            return $orderAmount;
        }
        $amount = $orderAmount->subtract($walletAmount);
        return new Money( $amount->getAmount(), new Currency('EUR'));
    }

    public function getChristmas($user_id)
    {
        try {
            return $this->orderStorageStrategy->findByChristmasKey($user_id)->returnValues();
        } catch (\RedisException $r) {
            return new ActionResult(false, $r->getMessage());
        }
    }

}