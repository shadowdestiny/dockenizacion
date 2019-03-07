<?php
namespace EuroMillions\web\services\card_payment_providers;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;
use Money\Money;

class FakeCardPaymentProvider implements ICardPaymentProvider
{

    /**
     * @var PaymentCountry $paymentCountry
     */
    protected $paymentCountry;

    /**
     * @var PaymentWeight $paymentWeight
     */
    protected $paymentWeight;

    public function __construct()
    {
        $this->paymentCountry = new PaymentCountry(['RU']);
        $this->paymentWeight= new PaymentWeight(70);
    }


    public function charge(Money $amount, CreditCard $card)
    {
        $result = $card->getLastNumbersOfCreditCard() % 2 == 0;
        return new ActionResult($result);
    }


    /**
     * @param User $user
     * @return mixed
     */
    public function user(User $user)
    {
    }

    public function type()
    {
        return new PaymentSelectorType(PaymentSelectorType::CREDIT_CARD_METHOD);
    }

    /**
     * @return PaymentWeight
     */
    public function getPaymentWeight()
    {
        return $this->paymentWeight;
    }

    /**
     * @return PaymentCountry
     */
    public function getPaymentCountry()
    {
        return $this->paymentCountry;
    }

}