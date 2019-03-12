<?php


namespace EuroMillions\web\services\card_payment_providers\royalpay;


use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Response;

class GatewayClientWrapper
{

    private $config;

    private $curlWrapper;

    public function __construct(RoyalPayConfig $config, Curl $curlWrapper = null)
    {
        $this->config = $config;
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }

    public function send(array $params, $action) {
        try {
            /** @var Response  $response */
            $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYHOST,false);
            $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYPEER,false);
            $response = $this->curlWrapper->post($this->config->getEndpoint().'/'.$action,
                json_encode($params),
                true,
                array(
                    "Content-Type: application/json; charset=utf-8",
                )
            );
            return $response;
        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }

    }

}
