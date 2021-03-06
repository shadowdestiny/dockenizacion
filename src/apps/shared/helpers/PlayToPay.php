<?php


namespace EuroMillions\shared\helpers;

use EuroMillions\shared\enums\PaymentProviderEnum;
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



        $paymentsCollection = $this->paymentProviderServiceTrait->createCollectionFromTypeAndCountry($this->paymentSelectorTypeTrait, $this->paymentCountryTrait);

        if( $paymentsCollection->getIterator()->current()->get()->getName() !== PaymentProviderEnum::ROYALPAY ) {

            if ($aPaymentProvider || $aPaymentProvider == 'true') {
                if ($lotteryName !== 'EuroMillions') { //TODO: nasty hack for use powerball service
                    //Play from PowerBallService
                    $play = $this->powerBallService;
                    return $play->play($user_id, $amount, $card, $withAccountBalance, $isWallet, $lotteryName, $paymentsCollection->getIterator()->current()->get());
                } else {
                    //Play from PlayService
                    $play = $this->playService;
                    return $play->play($user_id, $amount, $card, $withAccountBalance, $isWallet, $paymentsCollection->getIterator()->current()->get());
                }
            }
        }

        $play = $this->playService;

        return $play->playWithQueue(
            $user_id,
            $amount,
            $card,
            $withAccountBalance,
            $isWallet,
            $lotteryName,
            $paymentsCollection
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
