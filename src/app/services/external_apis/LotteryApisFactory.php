<?php
namespace EuroMillions\services\external_apis;

use EuroMillions\entities\Lottery;
use EuroMillions\interfaces\IJackpotApi;
use EuroMillions\interfaces\IResultApi;
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
        $object_name = '\EuroMillions\services\external_apis\\'.$lottery->getJackpotApi().'Api';
        return new $object_name($curlWrapper);
    }

    /**
     * @param Lottery $lottery
     * @param Curl $curlWrapper
     * @return IResultApi
     */
    public function resultApi(Lottery $lottery, Curl $curlWrapper = null)
    {
        $object_name = '\EuroMillions\services\external_apis\\'.$lottery->getResultApi().'Api';
        return new $object_name($curlWrapper);
    }
}