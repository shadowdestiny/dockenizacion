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
        $lottery = $order->getLottery();
        try
        {
            if($order->isNextDraw())
            {
                foreach ($order->getPlayConfig() as $playConfig)
                {
                    $result = $this->playService->validatorResult($lottery,$playConfig,new ActionResult(true, $order->getNextDraw()),$order);
                    if($result->success())
                    {
                        var_dump(__LINE__);
                    }
                }
            }
        } catch(\Exception $e)
        {
        }
    }



}