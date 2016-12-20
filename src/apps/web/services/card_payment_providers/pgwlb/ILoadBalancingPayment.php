<?php


namespace EuroMillions\web\services\card_payment_providers\pgwlb;


interface ILoadBalancingPayment
{
    public function makeStrategy();
    public function getInstance();
}