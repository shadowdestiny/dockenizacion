<?php


namespace EuroMillions\web\services\card_payment_providers\pgwlb;


use EuroMillions\web\services\card_payment_providers\payments_util\PaymentsRegistry;

class RandomStrategy implements ILoadBalancingPayment
{

    /** @var PaymentsRegistry */
    private $registry;
    private $instance;

    public function __construct(PaymentsRegistry $registry)
    {
        $this->registry=$registry;
        $this->makeStrategy();
    }

    public function makeStrategy()
    {
        $this->instance = $this->registry->getInstances()[array_rand($this->registry->getInstances())];
    }

    public function getInstance()
    {
        return $this->instance;
    }
}