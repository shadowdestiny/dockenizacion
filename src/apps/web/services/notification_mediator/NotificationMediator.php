<?php


namespace EuroMillions\web\services\notification_mediator;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ILogger;
use EuroMillions\web\services\LoggerFactory;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\WalletService;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\Order;
use Phalcon\Logger;

class NotificationMediator implements IMediatorNotification
{

    /**
     * @var WalletService $walletService
     */
    protected $walletService;

    /**
     * @var PlayService $playService
     */
    protected $playService;

    /**
     * @var TransactionService $transactionService
     */
    protected $transactionService;

    /**
     * @var LoggerFactory $loggerFactory
     */
    protected $loggerFactory;

    /**
     * @var Order $order
     */
    protected $order;

    /** @var User $user */
    protected $user;

    /** @var ILogger $logger*/
    protected $logger;

    /**
     * @var $walletBefore
     */
    protected $walletBefore;

    /**
     * @var $transactionID
     */
    protected $transactionID;


    /**
     * Mediator constructor.
     * @param WalletService $walletService
     * @param PlayService $playService
     * @param TransactionService $transactionService
     * @param ILogger $logger
     */
    public function __construct(WalletService $walletService, PlayService $playService, TransactionService $transactionService, ILogger $logger)
    {
        $this->walletService=$walletService;
        $this->playService=$playService;
        $this->transactionService=$transactionService;
        $this->logger=$logger;

        $this->walletService->setMediator($this);
        $this->playService->setMediator($this);
        $this->transactionService->setMediator($this);
        $this->logger->setMediator($this);
    }



    public function playConfigValidate()
    {
        foreach ($this->order->getPlayConfig() as $playConfig)
        {
            $playConfig->setLottery($this->order->getLottery());
            $playConfig->setDiscount(new Discount($this->order->getPlayConfig()[0]->getFrequency(),$this->playService->retrieveEuromillionsBundlePrice()));
            $result = $this->playService->validatorResult($this->order->getLottery(),$playConfig,new ActionResult(true, $this->order->getNextDraw()),$this->order);
            $this->log('playConfigValidate','Result validation->'.$result->success().':TransactiondID-> '.$this->transactionID,Logger::INFO);
            $isBetPersisted = $this->playService->persistBetDistinctEuroMillions($playConfig, new ActionResult(true, $this->order->getNextDraw()), $this->order, $result->getValues());
            if($result->success() && $isBetPersisted->success())
            {
                $this->walletService->extract($this->user,$this->order);
            }
            $this->log('playConfigValidate','PlayConfig->'.$playConfig->getId().':TransactiondID-> '.$this->transactionID,Logger::INFO);
        }
        $this->walletService->ticketPurchaseFromNotification($this->user,$this->order,$this->transactionID,$this->walletBefore);
    }

    public function persistBet()
    {

    }

    public function log($action,$message,$level)
    {
        $this->logger->log(json_encode([
            'action' => $action,
            'message' => $message,
            'user' => $this->user->getId()]),
            $level
        );
    }

    public function purchaseTransaction()
    {

    }

    public function sendEmail()
    {
        $this->playService->sendEmailPurchaseQueue($this->user, $this->order->getPlayConfig(), $this->order->getLottery()->getName());
        $this->log('sendEmail','Email PurchaseQueue sent',Logger::INFO);
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
        $this->log('updateTransaction','Transaction->'.$transactionID,Logger::INFO);
    }
}