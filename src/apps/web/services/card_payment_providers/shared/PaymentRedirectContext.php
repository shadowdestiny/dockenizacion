<?php


namespace EuroMillions\web\services\card_payment_providers\shared;


use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\royalpay\redirect_response\RoyalPayRedirectResponseStrategy;
use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;
use EuroMillions\web\services\card_payment_providers\widecard\redirect_response\WirecardRedirectResponseStrategy;
use Phalcon\Http\Client\Provider\Curl;


class PaymentRedirectContext
{

    /** @var IPaymentResponseRedirect $strategy */
    private $strategy;

    public function __construct(ICardPaymentProvider $paymentProvider, $lotteryName)
    {
        if($paymentProvider->getName() == PaymentProviderEnum::WIRECARD) {
            $this->strategy = new WirecardRedirectResponseStrategy($lotteryName);
        } else if($paymentProvider->getName() == PaymentProviderEnum::ROYALPAY)
            $this->strategy = new RoyalPayRedirectResponseStrategy(new Curl());
        else {
            $this->strategy = new NormalRedirectResponseStrategy($lotteryName);
        }
    }

    public function execute(PaymentBodyResponse $paymentBodyResponse)
    {
        return $this->strategy->redirectTo($paymentBodyResponse);
    }



}