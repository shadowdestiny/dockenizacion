<?php
namespace EuroMillions\services\external_apis;

use Phalcon\Http\Client\Provider\Curl;

class LotteryDotIeApi
{
    public function getResultForDate($date, Curl $curlWrapper = null)
    {
        $curl_wrapper = $curlWrapper ? $curlWrapper : new Curl();
        $response = $curl_wrapper->post(
            "http://resultsservice.lottery.ie/ResultsService.asmx/GetResultsForDate",
            ['drawType'=>'EuroMillions', 'drawDate'=> $date]
        );
        return $response->body;
    }
}