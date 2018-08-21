<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 21/08/18
 * Time: 18:09
 */

namespace EuroMillions\shared\services\payments_load_balancer_strategies;


use EuroMillions\web\services\card_payment_providers\pgwlb\ILoadBalancingPayment;
use Phalcon\Config;

class FallbackStrategy implements ILoadBalancingPayment
{

    protected $paymentsCollection;
    protected $instance;
    protected $params;

    public function __construct(\EuroMillions\shared\components\PaymentsCollection $payments, Config $params)
    {
        $this->paymentsCollection = $payments;
        $this->params = $params;
        $this->makeStrategy();
    }


    public function makeStrategy()
    {
        $this->instance = $this->paymentsCollection->getItem($this->params->instance);

    }

    public function getInstance()
    {
        return $this->instance;
    }
}