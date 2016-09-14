<?php


namespace EuroMillions\web\services\card_payment_providers\widecard;


use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Response;

class GatewayClientWrapper
{

    private $config;

    private $curlWrapper;

    public function __construct(WideCardConfig $config, Curl $curlWrapper = null)
    {
        $this->config = $config;
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }

    public function send(array $params) {
        try {
            /** @var Response  $response */
            $response = $this->curlWrapper->post($this->config->getEndpoint(),
                json_encode($params),
                true,
                [
                    "Content-Type: application/json; charset=utf-8",
                ]
            );
            return $response;
        } catch ( \Exception $e ) {

        }

    }

}