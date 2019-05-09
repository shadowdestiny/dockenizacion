<?php

namespace EuroMillions\web\vo\dto\order_payment_provider;

use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\Order;
use Phalcon\Config;

abstract class OrderPaymentProviderDTO extends DTOBase implements IDto, \JsonSerializable
{
    protected $userDto;
    protected $orderVo;
    protected $data;
    protected $config;


    public $totalPrice;
    public $currency;
    public $lottery;
    public $transactionID;
    public $isWallet;
    public $isMobile;
    public $urlEuroMillions;
    public $notificationEndpoint;

    public function __construct(UserDTO $userDto, Order $orderVo, array $data, Config $config)
    {
        $this->userDto = $userDto;
        $this->orderVo = $orderVo;
        $this->data = $data;
        $this->config = $config;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->totalPrice = $this->orderVo->getCreditCardCharge()->getFinalAmount()->getAmount();
        $this->currency = $this->orderVo->getCreditCardCharge()->getFinalAmount()->getCurrency()->getName();
        $this->lottery = $this->orderVo->getLottery()->getName();
        $this->isWallet = $this->orderVo->isIsCheckedWalletBalance();
        $this->isMobile =  $this->data['isMobile'] == 3 ? 'Desktop' : 'Mobile';
        $this->urlEuroMillions = $this->config['domain']->url;
        $this->transactionID = $this->orderVo->getTransactionId();
    }

    public function getUser()
    {
        return $this->userDto;
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
}
