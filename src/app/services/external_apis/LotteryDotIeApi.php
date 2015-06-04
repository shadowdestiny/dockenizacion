<?php
namespace EuroMillions\services\external_apis;

use EuroMillions\interfaces\IResultApi;
use Phalcon\Http\Client\Provider\Curl;

class LotteryDotIeApi implements IResultApi
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

    public function getJackpot()
    {
        $curl_wrapper = new Curl();
        $response = $curl_wrapper->post(
            "http://resultsservice.lottery.ie/ResultsService.asmx/GetProjectedJackpot",
            ['drawType'=>'EuroMillions']
        );
        return $response->body;
    }
}