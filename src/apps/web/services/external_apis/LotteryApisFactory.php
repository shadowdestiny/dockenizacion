<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\entities\Lottery;
use EuroMillions\web\interfaces\IJackpotApi;
use EuroMillions\web\interfaces\IResultApi;
use Phalcon\Http\Client\Provider\Curl;

class LotteryApisFactory
{
    /**
     * @param Lottery $lottery
     * @param Curl $curlWrapper
     * @return IJackpotApi
     */
    public function jackpotApi(Lottery $lottery, Curl $curlWrapper = null)
    {
        $object_name = '\EuroMillions\web\services\external_apis\\'.$lottery->getJackpotApi().'Api';
        return new $object_name($curlWrapper);
    }

    /**
     * @param Lottery $lottery
     * @param Curl $curlWrapper
     * @return IResultApi
     */
    public function resultApi(Lottery $lottery, Curl $curlWrapper = null)
    {
        $object_name = '\EuroMillions\web\services\external_apis\\'.$lottery->getResultApi().'Api';
        return new $object_name($curlWrapper);
    }

}