<?php

namespace EuroMillions\web\services\external_apis;

use EuroMillions\shared\interfaces\IFeatureFlagApi;
use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Di;

class FeatureFlagApi implements IFeatureFlagApi
{
    private $curlWrapper;
    private $config;

    /**
     * FeatureFlagApi constructor.
     * @param Curl|null $curlWrapper
     * @throws \Phalcon\Http\Client\Exception
     * @throws \Phalcon\Http\Client\Provider\Exception
     */
    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
        $this->config = Di::getDefault()->get('config')['featureflag_api'];
    }

    /**
     * @param $endpoint
     * @return mixed
     */
    public function sendGet($endpoint)
    {
        $drawBody = $this->curlWrapper->get($this->config->endpoint . $endpoint,
            [],
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;
        return $drawBody;
    }

    /**
     * @param $endpoint
     * @param $params
     * @return mixed
     */
    public function sendPost($endpoint, $params)
    {
        $drawBody = $this->curlWrapper->post($this->config->endpoint . $endpoint,
            json_encode($params),
            true,
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;

        return $drawBody;
    }

    /**
     * @param $endpoint
     * @param $params
     * @return mixed
     */
    public function sendPut($endpoint, $params)
    {
        $drawBody = $this->curlWrapper->put($this->config->endpoint . $endpoint,
            json_encode($params),
            true,
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;

        return $drawBody;
    }

    /**
     * @param $endpoint
     * @return mixed
     */
    public function sendDelete($endpoint)
    {
        $drawBody = $this->curlWrapper->delete($this->config->endpoint . $endpoint,
            [],
            true,
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;

        return $drawBody;
    }
}