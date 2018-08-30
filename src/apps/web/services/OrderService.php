<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 27/08/18
 * Time: 12:14
 */

namespace EuroMillions\web\services;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;

class OrderService
{

    /** @var PlayService $playService */
    protected $playService;

    protected $walletService;

    protected $transactionService;


    public function __construct(WalletService $walletService,
                                PlayService $playService,
                                TransactionService $transactionService)
    {
        $this->playService = $playService;
        $this->walletService = $walletService;
        $this->transactionService = $transactionService;
    }


    public function checkout($event,$component,array $data)
    {
        /** @var Order $order */
        $order = $data['order'];
        $transactionID = $data['transactionID'];
        /** @var User $user */
        $user = $order->getPlayConfig()[0]->getUser();
        $walletBefore = $user->getWallet();
        $lottery = $order->getLottery();
        try
        {
            if($order->isNextDraw())
            {
                $order->getPlayConfig()[0]->setLottery($order->getLottery());
                $user = $this->walletService->payOrder($user,$order);
                $transaction = $this->transactionService->getTransactionByEmTransactionID($transactionID)[0];
                $transaction->fromString();
                $transaction->setWalletBefore($walletBefore);
                $transaction->setWalletAfter($user->getWallet());
                $transaction->toString();
                $this->transactionService->updateTransaction($transaction);
                $walletBefore = $user->getWallet();
                foreach ($order->getPlayConfig() as $playConfig)
                {
                    $playConfig->setLottery($order->getLottery());
                    $playConfig->setDiscount(new Discount($order->getPlayConfig()[0]->getFrequency(),$this->playService->retrieveEuromillionsBundlePrice()));
                    $result = $this->playService->validatorResult($lottery,$playConfig,new ActionResult(true, $order->getNextDraw()),$order);
                    if($result->success())
                    {
                       $this->walletService->extract($user,$order);
                    }
                }
                $dataTransaction = [
                    'lottery_id' => $order->getLottery()->getId(),
                    'transactionID' => $transactionID,
                    'numBets' => count($order->getPlayConfig()),
                    'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                    'amountWithWallet' => $order->amountForTicketPurchaseTransaction(),
                    'walletBefore' => $walletBefore,
                    'amountWithCreditCard' => 0,
                    'playConfigs' => array_map(function ($val) {
                        return $val->getId();
                    }, $order->getPlayConfig()),
                    'discount' => $order->getDiscount()->getValue(),
                ];
                $this->walletService->purchaseTransactionGrouped($user, TransactionType::TICKET_PURCHASE, $dataTransaction);
            }
        } catch(\Exception $e)
        {

        }
    }





}