<?php


namespace EuroMillions\shared\helpers;

use EuroMillions\web\services\PaymentProviderService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\PowerBallService;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use Money\Money;

trait PlayToPay
{

    /**
     * @var PlayService
     */
    protected $playService;

    /**
     * @var PowerBallService
     */
    protected $powerBallService;

    /**
     * @var PaymentProviderService
     */
    protected $paymentProviderServiceTrait;

    /**
     * @var PaymentSelectorType
     */
    protected $paymentSelectorTypeTrait;

    /**
     * @var PaymentCountry
     */
    protected $paymentCountryTrait;


    public function payFromPlay(
        $user_id,
        Money $amount = null,
        CreditCard $card = null,
        $withAccountBalance = false,
        $isWallet = null,
        $lotteryName = "PowerBall",
        $aPaymentProvider
    ) {
        $play = $this->playService == null ? $this->powerBallService : $this->playService;
        if ($aPaymentProvider || $aPaymentProvider == 'true') {
            $paymentsCollection = $this->paymentProviderServiceTrait->createCollectionFromTypeAndCountry($this->paymentSelectorTypeTrait, $this->paymentCountryTrait);

            if ($this->powerBallService !== null) {
                //Play from PowerBallService
                return $play->play($user_id, $amount, $card, $withAccountBalance, $isWallet, $lotteryName, $paymentsCollection->getIterator()->current()->get());
            } else {
                //Play from playService
                return $play->play($user_id, $amount, $card, $withAccountBalance, $isWallet, $lotteryName, $paymentsCollection->getIterator()->current()->get());
            }
        }

        return $play->playWithQueue(
            $user_id,
            $amount,
            $card,
            $withAccountBalance,
            $isWallet,
            $lotteryName,
            $this->paymentProviderServiceTrait->createCollectionFromTypeAndCountry(
                $this->paymentSelectorTypeTrait,
                $this->paymentCountryTrait
            )
        );
    }


    /**
     * @param PlayService $playService
     * @return PlayToPay
     */
    public function setPlayService($playService)
    {
        $this->playService = $playService;
        return $this;
    }

    /**
     * @param PowerBallService $powerBallService
     * @return PlayToPay
     */
    public function setPowerBallService($powerBallService)
    {
        $this->powerBallService = $powerBallService;
        return $this;
    }

    /**
     * @param PaymentProviderService $paymentProviderServiceTrait
     * @return PlayToPay
     */
    public function setPaymentProviderServiceTrait($paymentProviderServiceTrait)
    {
        $this->paymentProviderServiceTrait = $paymentProviderServiceTrait;
        return $this;
    }

    /**
     * @param PaymentCountry $paymentCountryTrait
     * @return PlayToPay
     */
    public function setPaymentCountryTrait($paymentCountryTrait)
    {
        $this->paymentCountryTrait = $paymentCountryTrait;
        return $this;
    }

    /**
     * @param PaymentSelectorType $paymentSelectorTypeTrait
     * @return PlayToPay
     */
    public function setPaymentSelectorTypeTrait($paymentSelectorTypeTrait)
    {
        $this->paymentSelectorTypeTrait = $paymentSelectorTypeTrait;
        return $this;
    }
}
