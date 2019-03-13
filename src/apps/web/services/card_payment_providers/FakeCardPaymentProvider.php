<?php
namespace EuroMillions\web\services\card_payment_providers;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\dto\PaymentProviderDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;

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
        $this->paymentWeight= new PaymentWeight(100);
    }


    /**
     * @param PaymentProviderDTO $data
     * @return ActionResult
     */
    public function charge(PaymentProviderDTO $data)
    {
        $card = $this->createFakeCard($data->toArray());
        $result = $card->getLastNumbersOfCreditCard() % 2 == 0;
        return new ActionResult($result);
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

    private function createFakeCard(array $data) {
        return new CreditCard(
            new CardHolderName($data['cardHolderName']),
            new CardNumber($data['creditCardNumber']),
            new ExpiryDate($data['expirationMonth'].'/'.$data['expirationYear']),
            new CVV($data['cvv'])
        );
    }

}