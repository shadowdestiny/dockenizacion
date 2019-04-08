<?php


namespace EuroMillions\web\services\card_payment_providers\shared;


use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;
use EuroMillions\web\services\card_payment_providers\widecard\redirect_response\WirecardRedirectResponseStrategy;
use Phalcon\Http\Response;

class PaymentRedirectContext
{

    /** @var IPaymentResponseRedirect $strategy */
    private $strategy;

    public function __construct(ICardPaymentProvider $paymentProvider, $lotteryName)
    {
        if($paymentProvider->getName() == PaymentProviderEnum::WIRECARD) {
            $this->strategy = new WirecardRedirectResponseStrategy(new Response(), $lotteryName);
        } else {
            $this->strategy = new NormalRedirectResponseStrategy(new Response(), $lotteryName);
        }

    }

    public function execute(PaymentBodyResponse $paymentBodyResponse)
    {
        return $this->strategy->redirectTo($paymentBodyResponse);
    }



}