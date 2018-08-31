<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 31/08/18
 * Time: 13:44
 */

namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\vo\Order;

class ErrorDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try
        {
            $deposit = $this->order->getTotal()->getAmount();
            $lotteryName = $this->order->getLottery()->getName();

            return [
                'deposit' => $deposit,
                'lottery' => $lotteryName,
                'user_name' => $this->order->getPlayConfig()[0]->getUser()->getName()
            ];
        } catch (\Exception $e)
        {
            //TODO throw exception
        }
    }
}