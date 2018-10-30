<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 31/08/18
 * Time: 13:44
 */

namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\vo\Order;

class ErrorDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{

    protected $order;
    protected $user;
    protected $dateOrder;

    public function __construct(User $user,Order $order, $dateOrder)
    {
        $this->order = $order;
        $this->user = $user;
        $this->dateOrder= $dateOrder;
    }


    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try
        {
            $deposit = $this->order->getTotal()->getAmount();
            $lotteryName = $this->order->getLottery()->getName();
            return [
                'deposit_name' => $deposit,
                'lottery_name' => $lotteryName,
                'user_name' => $this->user->getName(),
                'date' => $this->dateOrder,
                'language' => $this->user->getLocale()
            ];
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}