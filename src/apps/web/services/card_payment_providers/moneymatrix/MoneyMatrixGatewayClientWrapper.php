<?php


namespace EuroMillions\web\services\card_payment_providers\moneymatrix;


use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Response;

class MoneyMatrixGatewayClientWrapper
{

    private $config;

    private $curlWrapper;

    public function __construct(MoneyMatrixConfig $config, Curl $curlWrapper = null)
    {
        $this->config = $config;
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }

    public function send($params,$action,$method='post') {
        try {
            /** @var Response  $response */
            $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYHOST,false);
            $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYPEER,false);
            if($method == 'get')
            {
                $response = $this->curlWrapper->get($this->config->getEndpoint().'/'.$action,
                    $params,
                    array(
                        "Content-Type: application/json; charset=utf-8",
                    )
                );
            } else
            {
                $response = $this->curlWrapper->post($this->config->getEndpoint().'/'.$action,
                    $params,
                    true,
                    array(
                        "Content-Type: application/json; charset=utf-8",
                    )
                );
            }
            return $response->body;
        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }

    }

}
