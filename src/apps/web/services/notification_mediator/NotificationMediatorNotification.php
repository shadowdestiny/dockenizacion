<?php


namespace EuroMillions\web\services\notification_mediator;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\WalletService;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\Order;

class NotificationMediatorNotification implements IMediatorNotification
{

    /**
     * @var
     */
    protected $walletService;

    /**
     * @var
     */
    protected $playService;

    /**
     * @var
     */
    protected $transactionService;

    /**
     * @var
     */
    protected $loggerFactory;

    /**
     * @var Order $order
     */
    protected $order;

    /** @var User $user */
    protected $user;

    protected $walletBefore;

    protected $transactionID;


    /**
     * Mediator constructor.
     * @param WalletService $walletService
     * @param PlayService $playService
     * @param TransactionService $transactionService
     */
    public function __construct(WalletService $walletService, PlayService $playService, TransactionService $transactionService)
    {
        $this->walletService=$walletService;
        $this->playService=$playService;
        $this->transactionService=$transactionService;

        $this->walletService->setMediator($this);
        $this->playService->setMediator($this);
        $this->transactionService->setMediator($this);
    }



    public function playConfigValidate()
    {
        foreach ($this->order->getPlayConfig() as $playConfig)
        {
            $playConfig->setLottery($this->order->getLottery());
            $playConfig->setDiscount(new Discount($this->order->getPlayConfig()[0]->getFrequency(),$this->playService->retrieveEuromillionsBundlePrice()));
            $result = $this->playService->validatorResult($this->order->getLottery(),$playConfig,new ActionResult(true, $this->order->getNextDraw()),$this->order);
            $isBetPersisted = $this->playService->persistBetDistinctEuroMillions($playConfig, new ActionResult(true, $this->order->getNextDraw()), $this->order, $result->getValues());
            if($result->success() && $isBetPersisted->success())
            {
                $this->walletService->extract($this->user,$this->order);
            }
        }
        $this->walletService->ticketPurchaseFromNotification($this->user,$this->order,$this->transactionID,$this->walletBefore);
    }

    public function persistBet()
    {

    }

    public function log()
    {

    }

    public function purchaseTransaction()
    {

    }

    public function sendEmail()
    {
        if($this->order->getLottery()->getName() == 'EuroMillions')
        {
            $this->playService->sendEmailPurchase($this->user,$this->order->getPlayConfig());
        }
        if($this->order->getLottery()->getName() == 'PowerBall')
        {
            $this->playService->sendEmailPowerBallPurchase($this->user,$this->order->getPlayConfig());
        }
    }


    public function updateTransaction(User $user, Order $order, $transactionID, $walletBefore)
    {
        $this->user = $user;
        $this->order = $order;
        $this->walletBefore=$user->getWallet();
        $this->transactionID = $transactionID;
        $transactions = $this->transactionService->getTransactionByEmTransactionID($transactionID);
        $transactions[0]->fromString();
        $transactions[0]->setWalletBefore($walletBefore);
        $transactions[0]->setWalletAfter($user->getWallet());
        $transactions[0]->toString();
        $this->transactionService->updateTransaction($transactions[0]);
    }
}