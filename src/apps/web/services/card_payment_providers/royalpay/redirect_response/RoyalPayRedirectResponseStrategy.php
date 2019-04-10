<?php


namespace EuroMillions\web\services\card_payment_providers\royalpay\redirect_response;

use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;
use Phalcon\Http\Client\Provider\Curl;

class RoyalPayRedirectResponseStrategy implements IPaymentResponseRedirect
{
    protected $client;
    protected $lotteryName;

    public function __construct(Curl $curl, $lotteryName)
    {
        $this->client = $curl;
        $this->lotteryName = strtolower($lotteryName);
    }


    public function redirectTo(PaymentBodyResponse $paymentBodyResponse)
    {
        if ($paymentBodyResponse->getStatus()) {
            try {
                $method = $paymentBodyResponse->getMetadata()['redirect_method'];
                $url = $paymentBodyResponse->getMetadata()['url'];
                if ($method === 'GET') {
                    $urlToRedirect = $url . '?' . http_build_query($paymentBodyResponse->getMetadata()['redirect_params']);
                    header("Location:" . $urlToRedirect);
                } else {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($ch, CURLOPT_VERBOSE, 1);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, 0);
                    curl_setopt($ch, CURLOPT_POSTREDIR, 3);
                    curl_setopt(
                        $ch,
                        CURLOPT_POSTFIELDS,
                        http_build_query($paymentBodyResponse->getMetadata()['redirect_params'])
                    );
                    curl_exec($ch);
                    $redirectURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                    curl_close($ch);
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            if ($this->lotteryName === "deposit") {
                header("Location: " . "https://" . $_SERVER['HTTP_HOST'] . '/account/wallet');
            } else {
                header("Location: " . "https://" . $_SERVER['HTTP_HOST'] . '/euromillions/result/failure');
            }
        }
    }
}
