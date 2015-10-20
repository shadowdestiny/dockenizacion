<?php


namespace EuroMillions\services\external_apis;


use EuroMillions\interfaces\ILotteryValidation;
use Phalcon\Http\Client\Provider\Curl;

class LotteryValidationCastilloApi implements ILotteryValidation
{

    private $curlWrapper;

    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }

    public function request($bet)
    {
        $this->curlWrapper->post('https://www.loteriacastillo.com/euromillions');

    }

    public function response()
    {

    }
}