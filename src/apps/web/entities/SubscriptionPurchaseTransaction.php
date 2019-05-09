<?php


namespace EuroMillions\web\entities;


use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\exceptions\BadEntityInitializationException;
use EuroMillions\web\interfaces\ITransactionData;

class SubscriptionPurchaseTransaction extends PurchaseTransaction implements ITransactionData
{

    protected $hasFee;
    protected $amountAdded;
    protected $discount;
    protected $status;
    protected $lotteryName;
    protected $withWallet;
    protected $paymentProviderName;



    public function __construct(array $data)
    {
        $this->setLotteryId($data['lottery_id']);
        $this->setNumBets($data['numBets']);
        $this->setHasFee($data['feeApplied']);
        $this->setWalletBefore($data['walletBefore']);
        $this->setWalletAfter($data['walletAfter']);
        $this->setAmountAdded($data['amount']);
        $this->setTransactionID($data['transactionID']);
        $this->setPlayConfigs($data['playConfigs']);
        $this->setDate($data['now']);
        $this->setUser($data['user']);
        $this->setPendingBalanceAmount($data['amountWithCreditCard'] + $data['amountWithWallet']);
        $this->setLotteryName($data['lotteryName']);
        $this->setStatus(!empty($data['status']) ? $data['status'] : 'SUCCESS');
        $this->setWithWallet($data['withWallet']);
        $this->setPaymentProviderName( !empty($data['paymentProviderName']) ? $data['paymentProviderName'] : PaymentProviderEnum::WIRECARD);
    }


    /**
     * @return mixed
     */
    public function getLotteryId()
    {
        return $this->lotteryId;
    }

    /**
     * @param mixed $lotteryId
     */
    public function setLotteryId($lotteryId)
    {
        $this->lotteryId = $lotteryId;
    }

    /**
     * @return mixed
     */
    public function getNumBets()
    {
        return $this->numBets;
    }

    /**
     * @param mixed $numBets
     */
    public function setNumBets($numBets)
    {
        $this->numBets = $numBets;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function toString()
    {
        $this->data = $this->getLotteryId().'#'.$this->getHasFee().'#'.$this->getAmountAdded().'#'.$this->getDiscount().'#'.$this->getStatus().'#'.$this->getLotteryName(). '#'.$this->withWallet.'#'.$this->paymentProviderName;
    }

    public function fromString()
    {
        try
        {
            $arr=explode('#',$this->data);
            $count=count($arr);

            switch($count)
            {
                case 2:
                    list($lotteryId,$fee)= $arr;
                    break;
                case 3:
                    list($lotteryId,$fee,$amount)= $arr;
                    break;
                case 4:
                    list($lotteryId,$fee,$amount,$discount)= $arr;
                    break;
                case 5:
                    list($lotteryId,$fee,$amount,$discount,$status)= $arr;
                    break;
                case 6:
                    list($lotteryId,$fee,$amount,$discount,$status,$lotteryName) = $arr;
                    break;
                default:
                    list($lotteryId,$fee,$amount,$discount,$status,$lotteryName,$withWallet,$paymentProviderName) = $arr;
            }

            $this->lotteryId = isset($lotteryId) && $lotteryId!=''? $lotteryId: 0;
            $this->hasFee = isset($fee) && $fee!=''? $fee: '';
            $this->amountAdded = isset($amount) && $amount!=''? $amount: 0;
            $this->discount = isset($discount) && $discount!=''? $discount: '';
            $this->status = isset($status) && $status!=''? $status: 'PENDING';
            $this->lotteryName = isset($lotteryName) && $lotteryName!=''? $lotteryName: 'NONE';
            $this->withWallet = isset($withWallet) && $withWallet!=''? $withWallet: 0;
            $this->paymentProviderName = !empty($paymentProviderName) ? $paymentProviderName : PaymentProviderEnum::WIRECARD;
            return $this;
        } catch ( \Exception $e ) {
            throw new BadEntityInitializationException('Invalid data format');
        }
    }

    public function getEntityType()
    {
        return parent::SUBSCRIPTION_PURCHASE_TYPE;
    }

    /**
     * @return mixed
     */
    public function getHasFee()
    {
        return $this->hasFee;
    }

    /**
     * @param mixed $hasFee
     */
    public function setHasFee($hasFee)
    {
        $this->hasFee = $hasFee;
    }

    /**
     * @return mixed
     */
    public function getAmountAdded()
    {
        return $this->amountAdded;
    }

    /**
     * @param mixed $amountAdded
     */
    public function setAmountAdded($amountAdded)
    {
        $this->amountAdded = $amountAdded;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getLotteryName()
    {
        return $this->lotteryName;
    }

    /**
     * @param mixed $lotteryName
     */
    public function setLotteryName($lotteryName)
    {
        $this->lotteryName = $lotteryName;
    }

    /**
     * @return mixed
     */
    public function getWithWallet()
    {
        return $this->withWallet;
    }

    /**
     * @param mixed $withWallet
     */
    public function setWithWallet($withWallet)
    {
        $this->withWallet = $withWallet;
    }

    /**
     * @return mixed
     */
    public function getPaymentProviderName()
    {
        return $this->paymentProviderName;
    }

    /**
     * @param mixed $paymentProviderName
     */
    public function setPaymentProviderName($paymentProviderName)
    {
        $this->paymentProviderName = $paymentProviderName;
    }
}