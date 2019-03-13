<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\services\card_payment_providers\royalpay\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\royalpay\RoyalPayConfig;
use EuroMillions\web\services\card_payment_providers\shared\CountriesCollection;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;
use Money\Money;
use Phalcon\Http\Client\Response;

class RoyalPayPaymentProvider implements ICardPaymentProvider,IHandlerPaymentGateway
{

    use CountriesCollection;

    private $gatewayClient;
    private $data = [];
    /**
     * @var  User $user
     */
    private $user;
    /**
     * @var RoyalPayConfig $config
     */
    private $config;
    /**
     * @var PaymentCountry $paymentCountry
     */
    protected $paymentCountry;
    /**
     * @var PaymentWeight $paymentWeight
     */
    protected $paymentWeight;

    public function __construct(RoyalPayConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config);
        $this->config = $config;
        $this->paymentCountry = new PaymentCountry($this->countries());
        $this->paymentWeight= new PaymentWeight(100);
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
            "CallbackUrl" => "https://8bd12105.eu.ngrok.io/notification",
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
        return new PaymentSelectorType(PaymentSelectorType::CREDIT_CARD_METHOD);
    }

    /**
     * @return PaymentCountry
     */
    public function getPaymentCountry()
    {
        return $this->paymentCountry;
    }

    /**
     * @return PaymentWeight
     */
    public function getPaymentWeight()
    {
        return $this->paymentWeight;
    }
}