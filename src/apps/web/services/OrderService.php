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
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Order;

class OrderService
{

    public function __construct(WalletService $walletService,
                                PlayService $playService)
    {
        $this->playService = $playService;
        $this->walletService = $walletService;
    }


    public function checkout($event,$component,Order $order)
    {
        /** @var User $user */
        $user = $order->getPlayConfig()[0]->getUser();
        $walletBefore = $user->getWallet();
        $lottery = $order->getLottery();
        try
        {
            if($order->isNextDraw())
            {
                $this->walletService->payOrder($user,$order);
                foreach ($order->getPlayConfig() as $playConfig)
                {
                    $playConfig->setLottery($order->getLottery());
                    $result = $this->playService->validatorResult($lottery,$playConfig,new ActionResult(true, $order->getNextDraw()),$order);
                    if($result->success())
                    {
                       $this->walletService->extract($user,$order);
                    }
                }
                //TODO:
                $dataTransaction = [
                    'lottery_id' => 1,
                    'transactionID' => 1243242,
                    'numBets' => count($order->getPlayConfig()),
                    'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
                    'amountWithWallet' => $lottery->getSingleBetPrice()->multiply(count($order->getPlayConfig()))->getAmount(),
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