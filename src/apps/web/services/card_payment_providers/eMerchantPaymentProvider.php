<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\emerchant\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\emerchant\eMerchantConfig;
use EuroMillions\web\vo\CreditCard;
use Money\Money;
use Phalcon\Http\Client\Response;

class eMerchantPaymentProvider implements ICardPaymentProvider
{

    private $gatewayClient;
    /** @var  User $user */
    private $user;
    private $data = [];

    public function __construct(eMerchantConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config);
    }

    public function __get($name) {
        return $this->data[$name];
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * @param Money $amount
     * @param CreditCard $card
     * @return PaymentProviderResult
     */
    public function charge(Money $amount, CreditCard $card)
    {
        $params = $this->createArrayData($amount, $card);
        /** @var Response $result */
        $result = $this->gatewayClient->send($params);
        $header = $result->header;
        $body = json_decode($result->body);
        if( $header->statusCode != 200 ) {
            return new PaymentProviderResult(false,$header->statusMessage,$header->statusMessage);
        }
        return new PaymentProviderResult($body->status === "ok", $header->statusMessage);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function user(User $user)
    {
        $this->user = $user;
    }

    private function createArrayData(Money $amount, CreditCard $card) {
        return [
            'idTransaction' => $this->data['idTransaction'],
            'userId' => $this->user->getId(),
            'amount' => $amount->getAmount(),
            'creditCardNumber' => $card->cardNumber()->toNative(),
            'cvc' => $card->cvv()->toNative(),
            'expirationYear' => substr($card->expiryDate()->getYear(), 2),
            'expirationMonth' => $card->expiryDate()->getMonth(),
            'cardHolderName' => $card->cardHolderName()->toNative(),
            'email' => $this->user->getEmail()->toNative(),
            'ip' => $this->user->getIpAddress()->toNative(),
        ];
    }
}