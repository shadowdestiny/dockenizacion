<?php

namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IUser;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\enum\MoneyMatrixEndpoint;
use Phalcon\Config;

class OrderPaymentProviderDTO  extends DTOBase implements IDto
{
    public $MMdata;

    public $user;

    public $totalPrice;

    public $currency;

    public $lottery;

    public $transactionID;

    public $isWallet;

    public $urlEuroMillions;

    public $notificationEndpoint;

    public $isMobile;

    protected $config;

    public function __construct(array $data, Config $config)
    {

        /** @var User $user */
        $this->user = $data['user'];
        $this->totalPrice = $data['total'];
        $this->currency = $data['currency'];
        $this->lottery = $data['lottery'];
        $this->isWallet = $data['isWallet'] == 'true' ? true : false;
        $this->isMobile = $data['isMobile'] == 3 ? 'Desktop' : 'Mobile';
        $this->urlEuroMillions = $config['domain']->url;
        $this->notificationEndpoint = $config['moneymatrix']->endpoint;
        $this->transactionID = $data['transactionId'];
        $this->config = $config;
        $this->user->getId();
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->MMdata = $this->createDataMoneyMatrix();
    }

    public function toArray()
    {
        throw new \Exception('Method not implemented');
    }

    protected function createDataMoneyMatrix()
    {
        return $this->MMdata = [
            "orderID" => $this->getTransactionID(),
            "userID" => $this->user->getId(),
            "firstName" => $this->user->getName(),
            "lastName" => $this->user->getSurname(),
            "emailAddress" => $this->user->getEmail()->toNative(),
            "countryCode" =>  strtoupper($this->user->getDefaultLanguage()) == 'EN' ? 'GB' : strtoupper($this->user->getDefaultLanguage()),
            "CallbackUrl" => $this->notificationEndpoint.'/notification',
            "ipAddress" => $this->user->getIpAddress()->toNative(),
            "address" => $this->user->getStreet() == null ? "" : $this->user->getStreet(),
            "city" => $this->user->getCity() == null ? "" : $this->user->getCity(),
            "phoneNumber" => $this->user->getPhoneNumber() == null ? "" : $this->user->getPhoneNumber(),
            "postalCode" => $this->user->getZip() == null ? "" : $this->user->getZip(),
            "state" => "",
            "birthDate" => "",
            "paymentMethod" => "null",
            "amount" =>  number_format($this->totalPrice / 100,2),
            "currency" => 'EUR',
            "SuccessUrl" => "https://".$this->urlEuroMillions.$this->lottery.'/result/success',
            "FailUrl" => "https://".$this->urlEuroMillions.$this->lottery.'/result/failure',
            "CancelUrl" => "https://".$this->urlEuroMillions.$this->lottery.'/result/cancel',
            "CheckStatusUrl" => "https://".$this->urlEuroMillions.$this->lottery.'/result/status',
            "channel" => $this->isMobile,
            "allowPaySolChange" => "true",
            "registrationIpAddress" => $this->user->getIpAddress()->toNative(),
            "registrationDate" => ""
        ];
    }

    /**
     * @return mixed
     */
    public function getTransactionID()
    {
        return $this->transactionID;
    }

    /**
     * @param mixed $transactionID
     */
    public function setTransactionID($transactionID)
    {
        $this->transactionID = $transactionID;
    }

    public function toJson()
    {
        return json_encode($this->MMdata);
    }

    public function action()
    {
        return MoneyMatrixEndpoint::DEPOSIT;
    }

}