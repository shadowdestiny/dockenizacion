<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 2/08/18
 * Time: 11:03
 */

namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixConfig;

class MoneyMatrixPaymentStrategy implements ICreditCardStrategy
{

    private $config;

    public function __construct($config = null)
    {
        $this->config = $config;
    }

    public function get()
    {
        return new MoneyMatrixPaymentProvider(new MoneyMatrixConfig($this->config['endpoint']));
    }
}