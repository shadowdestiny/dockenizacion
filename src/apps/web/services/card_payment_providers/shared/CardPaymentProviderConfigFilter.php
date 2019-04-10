<?php


namespace EuroMillions\web\services\card_payment_providers\shared;

use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;

class CardPaymentProviderConfigFilter
{

    protected $countries;
    protected $weight;

    use CountriesCollection;

    public function __construct($weight = 100, $countries = null)
    {
        $this->weight = new PaymentWeight($weight);

        if (empty($countries) || $countries == "") {
            $countries = $this->countries();
        } else {
            $countries = explode(",", $countries);
        }

        $this->countries = new PaymentCountry($countries);
    }

    /**
     * @return PaymentCountry
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @return PaymentWeight
     */
    public function getWeight()
    {
        return $this->weight;
    }
}