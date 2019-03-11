<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\services\card_payment_providers\shared\CountriesCollection;
use EuroMillions\web\services\card_payment_providers\widecard\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;
use Money\Money;
use Phalcon\Http\Client\Response;

class WideCardPaymentProvider implements ICardPaymentProvider,IHandlerPaymentGateway
{

    use CountriesCollection;

    private $gatewayClient;
    /** @var  User $user */
    private $user;
    private $data = [];
    /** @var WideCardConfig $config */
    private $config;

    /**
     * @var PaymentCountry $paymentCountry
     */
    protected $paymentCountry;

    /**
     * @var PaymentWeight $paymentWeight
     */
    protected $paymentWeight;

    public function __construct(WideCardConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config);
        $this->config = $config;
        $this->paymentCountry = new PaymentCountry($this->countries());
        $this->paymentWeight= new PaymentWeight(50);
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

    public function withDraw(Money $amount, $idTransaction)
    {
        $result = $this->gatewayClient->send(['idTransaction' => $idTransaction[0]['transactionID'],
                                              'amount' => $amount->getAmount() ]);

        //TODO: in this case, is called from admin only
        return $result;
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
            'expirationYear' => $card->expiryDate()->getYear(),
            'expirationMonth' => $card->expiryDate()->getMonth(),
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