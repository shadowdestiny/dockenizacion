<?php


namespace EuroMillions\web\services\card_payment_providers\widecard\redirect_response;


use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use Phalcon\Http\Client\Provider\Curl;

class WirecardRedirectResponseStrategy implements IPaymentResponseRedirect
{

    protected $client;

    public function __construct(Curl $curl = null)
    {
        $this->client = $curl ? $curl : new Curl();
    }

    public function redirectTo(...$params)
    {
        $endpoint = $params[0]['url'];
        $lotteryName = $params[0]['lottery'];
        $status = $params[0]['status'];
        try {
            $this->client->setOption(CURLOPT_FOLLOWLOCATION, true);
            $this->client->post('https://dev.euromillions.com/'.$lotteryName.'/result/success');
        } catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

    }
}