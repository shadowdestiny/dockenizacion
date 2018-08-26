<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\services\SiteConfigService;
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

    public function __construct(EntityManager $entityManager, IPlayStorageStrategy $orderStorageStrategy, SiteConfigService $siteConfigService, WalletService $walletService)
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
            $result = $this->orderStorageStrategy->save($order->toJsonData(), $user_id);
            if ($result->success()) {
                return $result;
            } else {
                return new ActionResult(false);
            }
        }
        return new ActionResult(false);
    }

    public function get($user_id, $lotteryName)
    {
        try {
            /** @var ActionResult $result */
            $result = $this->orderStorageStrategy->findByKey($user_id);
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
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
                        $playConfig->formToEntity($user, $bet, $bet->euromillions_line);
                        $bets[] = $playConfig;
                    }
                    $fee = $this->siteConfigService->getFee();
                    $fee_limit = $this->siteConfigService->getFeeToLimitValue();
                    $order = OrderFactory::create(
                        $bets,
                        $lottery->getSingleBetPrice(),
                        $fee, $fee_limit,
                        new Discount($bets[0]->getFrequency(), $this->playConfigRepository->retrieveEuromillionsBundlePrice()),
                        $lottery
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

    public function checkout($event,$component, $data)
    {
        //meter fecha del siguiente draw en la order... Tiene que estar todo en la order
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