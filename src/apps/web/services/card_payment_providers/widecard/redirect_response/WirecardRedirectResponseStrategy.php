<?php


namespace EuroMillions\web\services\card_payment_providers\widecard\redirect_response;


use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;

class WirecardRedirectResponseStrategy implements IPaymentResponseRedirect
{

    protected $lotteryName;

    public function __construct($lotteryName)
    {
        $this->lotteryName = strtolower($lotteryName);
    }

    public function redirectTo(PaymentBodyResponse $paymentBodyResponse)
    {
        if($paymentBodyResponse->getStatus()) {
            header("Location: " . "https://" . $_SERVER['HTTP_HOST'] .'/'.$this->lotteryName. '/result/success');
        } else {
            header("Location: " . "https://" . $_SERVER['HTTP_HOST'] .'/'.$this->lotteryName. '/result/failure');
        }
    }
}