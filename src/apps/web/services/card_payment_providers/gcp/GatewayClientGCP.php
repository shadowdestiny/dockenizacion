<?php


namespace EuroMillions\web\services\card_payment_providers\gcp;

use EuroMillions\web\entities\User;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\Order;
use Money\Money;
use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Cookie;
use Phalcon\Http\Request;
use Phalcon\Http\Response;

class GatewayClientGCP
{

    private $url;
    private $merchantId;
    private $gatewayNo;
    private $signKey;
    /** @var  CreditCard */
    private $creditCard;
    private $signInfo;
    /** @var  User */
    private $user;
    /** @var  Money */
    private $amount;


    private $curlWrapper;

    public function __construct($url, $merchantId, $gatewayNo, $signKey, Curl $curlWrapper = null)
    {
        $this->url = $url;
        $this->merchantId = $merchantId;
        $this->gatewayNo = $gatewayNo;
        $this->signKey = $signKey;
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }


    public function send()
    {
        if( $this->creditCard == null ) {
            throw new \Exception('No credit card set');
        }

        if( $this->user == null ) {
            throw new \Exception('No user set');
        }

        try {
            $this->signInfo = $this->signInfo();
            $params = $this->getHttpParams();
            $this->exec($params);
        } catch ( \Exception $e ) {
            throw new \Exception('Error with payment provider');
        }
    }


    private function getHttpParams()
    {
        $arr = [
            'merNo'            => $this->merchantId,            //MerchantNo.
            'gatewayNo'        => $this->gatewayNo,        //GatewayNo.
            'orderNo'          => 'EM1',          //OrderNo.
            'orderCurrency'    => 'EUR',    //OrderCurrency
            'orderAmount'      => $this->amount->getAmount() / 100,      //OrderAmount
            'firstName'        => $this->user->getName(),        //FirstName
            'lastName'         => $this->user->getSurname(),         //lastName
            'cardNo'           => $this->creditCard->cardNumber()->toNative(),           //CardNo
            'cardExpireMonth'  => $this->creditCard->getExpiryMonth(),  //CardExpireMonth
            'cardExpireYear'   => $this->creditCard->getExpiryYear(),   //CardExpireYear
            'cardSecurityCode' => $this->creditCard->getCVV(),
            'issuingBank'      => 'bank',
            'email'            => $this->user->getEmail()->toNative(),
            'returnUrl'        => $_COOKIE['url_gcp'],
            'phone'            => '123456789', //$this->user->getPhoneNumber(),
            'country'          => 'ES',//$this->user->getCountry(),
            'state'            => 'NA',
            'city'             => 'Barcelona',
            'address'          => 'Consell de cent',
            'zip'              => '08007',
            'signInfo'         => $this->signInfo,
            'csid'             => $_COOKIE['csid']
        ];

        return http_build_query($arr);
    }

    /**
     * @param mixed $creditCard
     */
    public function setCreditCard($creditCard)
    {
        $this->creditCard = $creditCard;
    }

    private function signInfo()
    {
        return hash("sha256" , $this->merchantId.$this->gatewayNo .'EM1'.'EUR'.($this->amount->getAmount() /100).$this->user->getName() .$this->user->getSurname().$this->creditCard->cardNumber()->toNative().$this->creditCard->getExpiryYear().$this->creditCard->getExpiryMonth().$this->creditCard->getCVV().$this->user->getEmail()->toNative().$this->signKey);
    }


    private function exec($params)
    {
        $request = new Request();
        $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYPEER, 0);
        $this->curlWrapper->setOption(CURLOPT_HEADER,0);
        $this->curlWrapper->setOption(CURLOPT_RETURNTRANSFER, 0);
        $this->curlWrapper->setOption(CURLOPT_POST,1);
        $this->curlWrapper->setOption(CURLOPT_FOLLOWLOCATION, 0);
        $this->curlWrapper->setOption(CURLOPT_POSTFIELDS, $params);
        $this->curlWrapper->setOption(CURLOPT_REFERER, $request->getHTTPReferer());
        $this->curlWrapper->setOption(CURLOPT_HTTPHEADER, ['X-FORWARDED-FOR:'.$request->getClientAddress(), 'CLIENT-IP:'.$request->getClientAddress()]);
        $this->curlWrapper->post($this->url);
    }

    public function renewConfig(GCPConfig $config)
    {
        $name = $this->creditCard->getCompany();
        $this->gatewayNo = $name == 'Visa' ? $config->getVisaGateway() : $config->getMasterGateway();
        $this->signKey = $name == 'Visa' ? $config->getVisaSignKey() : $config->getMasterSignKey();
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @param Money $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

}