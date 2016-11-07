<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\services\SiteConfigService;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;

class CartService
{

    private $entityManager;

    private $orderStorageStrategy;

    private $userRepository;

    /** @var SiteConfigService $siteConfigService */
    private $siteConfigService;

    public function __construct( EntityManager $entityManager, IPlayStorageStrategy $orderStorageStrategy, SiteConfigService $siteConfigService )
    {
        $this->entityManager = $entityManager;
        $this->orderStorageStrategy = $orderStorageStrategy;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->siteConfigService = $siteConfigService;
    }

    public function store( Order $order )
    {
        $user_id = $order->getPlayConfig()[0]->getUser()->getId();
        if( null !== $user_id ) {
            /** @var ActionResult $result */
            $result = $this->orderStorageStrategy->save($order->toJsonData(), $user_id);
            if( $result->success() ) {
                return $result;
            } else {
                return new ActionResult(false);
            }
        }
        return new ActionResult(false);
    }

    public function get( $user_id )
    {
        try {
            /** @var ActionResult $result */
            $result = $this->orderStorageStrategy->findByKey($user_id);
            if($result->success()) {
                $json = json_decode($result->returnValues());
                if( NULL == $json ) {
                    return new ActionResult(false);
                }
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                if( null !== $user ) {
                    $bets = [];
                    foreach($json->play_config as $bet) {
                        $playConfig = new PlayConfig();
                        $playConfig->formToEntity($user,$bet,$bet->euromillions_line);
                        $bets[] = $playConfig;
                    }
                    $fee = $this->siteConfigService->getFee();
                    $fee_limit = $this->siteConfigService->getFeeToLimitValue();
                    $single_bet_price = new Money((int) $json->single_bet_price, new Currency('EUR'));
                    $order = new Order($bets,$single_bet_price, $fee, $fee_limit);//order created
                    if( null !== $order ) {
                        return new ActionResult(true, $order);
                    }
                }
            } else {
                return new ActionResult(false, 'Order doesn\'t exist');
            }
        } catch ( \RedisException $r ) {
            return new ActionResult(false, $r->getMessage());
        }
        return new ActionResult(false);
    }

}