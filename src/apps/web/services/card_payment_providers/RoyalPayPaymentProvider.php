<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\services\card_payment_providers\royalpay\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\royalpay\RoyalPayConfig;
use EuroMillions\web\vo\CreditCard;
use Money\Money;
use Phalcon\Http\Client\Response;

class RoyalPayPaymentProvider implements ICardPaymentProvider,IHandlerPaymentGateway
{

    private $gatewayClient;
    /** @var  User $user */
    private $user;
    private $data = [];
    /** @var RoyalPayConfig $config */
    private $config;

    public function __construct(RoyalPayConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config);
        $this->config = $config;
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
        $result = $this->gatewayClient->send($params, 'deposit');
        $header = $result->header;
        $body = json_decode($result->body);

        if( $header->statusCode != 201 ) {
            return new PaymentProviderResult(false, $header->statusMessage, $header->statusMessage);
        }

        return new PaymentProviderResult($body->status === "created", $header->statusMessage);

    }

    public function withDraw(Money $amount, $idTransaction)
    {
        throw new \BadFunctionCallException();
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
            'orderID' => $this->data['idTransaction'],
            'userID' => (string) $this->user->getId(),
            'amount' => $amount->getAmount(),
            'currency' => $amount->getCurrency()->getName(),
            "CallbackUrl" => "https://719ab784.eu.ngrok.io/notification",
	        "SuccessUrl" => "https://dev.euromillions.com/euromillions/result/success",
            "FailUrl" => "https://dev.euromillions.com/euromillions/result/failure",
            "PendingUrl" => "https://dev.euromillions.com/euromillions/result/success",
            'cardNumber' => $card->cardNumber()->toNative(),
            'cardCvv' => $card->cvv()->toNative(),
            'cardYear' => $card->expiryDate()->getYear(),
            'cardMonth' => $card->expiryDate()->getMonth(),
            'cardHolderName' => $card->cardHolderName()->toNative()
        ];
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function call($data,$action,$method)
    {
        // TODO: Implement call() method.
    }

    public function type()
    {
        return "FORM";
    }
}