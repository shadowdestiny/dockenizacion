<?php


namespace EuroMillions\web\services\card_payment_providers\shared;


use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;
use Phalcon\Http\Response;

class NormalRedirectResponseStrategy implements IPaymentResponseRedirect
{

    protected $client;

    protected $lotteryName;

    public function __construct(Response $response, $lotteryName)
    {
        $this->client = $response;
        $this->lotteryName = strtolower($lotteryName);
    }

    public function redirectTo(PaymentBodyResponse $paymentBodyResponse)
    {
        if($paymentBodyResponse->getStatus()) {
            $this->client->redirect('/' . $this->lotteryName . '/result/success');
            $this->client->send();
        } else {
            $this->client->redirect('/' . $this->lotteryName . '/result/failure');
            $this->client->send();
        }
    }
}