<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:52
 */

namespace EuroMillions\web\services\external_apis;


use EuroMillions\web\interfaces\IJackpotApi;
use EuroMillions\web\interfaces\IResultApi;
use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Phalcon\Di;
use Phalcon\Http\Client\Provider\Curl;


class LottorisqApi implements IResultApi, IJackpotApi
{

    protected $curlWrapper;

    protected $config;

    protected $currencyConversionService;

    public function __construct(Curl $curlWrapper = null, CurrencyConversionService $currencyConversionService = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
        $this->config = Di::getDefault()->get('config')['lotto_api'];
        $this->currencyConversionService =  $currencyConversionService ? $currencyConversionService : Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
    }

    public function getJackpotForDate($lotteryName, $date)
    {
        $drawBody = $this->curlWrapper->get($this->config->endpoint.'/results')->body;
        $draw = json_decode($drawBody, true)[0];
        $currency = $this->currencyConversionService->convert(new Money((int) $draw['jackpot']['total'], new Currency('USD')),
                                                              new Currency('EUR'));

        return new Money((int) floor($currency->getAmount() / 1000000) * 100000000, new Currency('EUR'));
    }

    public function getResultForDate($lotteryName, $date)
    {
        try {
            if($date != null) {
                $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYHOST,false);
                $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYPEER,false);
                $drawBody = $this->curlWrapper->get($this->config->endpoint.'/results'.'/'.$date,
                    [],
                    array(
                        "x-api-key: " .$this->config->api_key,
                        "Content-Type: application/json; charset=utf-8",
                    )
                )
                ->body;
                $draw = json_decode($drawBody, true);
            } else {
                $drawBody = $this->curlWrapper->get($this->config->endpoint.'/results',
                    [],
                    array(
                        "x-api-key: " .$this->config->api_key,
                        "Content-Type: application/json; charset=utf-8",
                    )
                )
                ->body;
                $draw = json_decode($drawBody, true)[0];
            }
            return $draw;
        } catch ( \Exception $e) {
            return [];
        }
    }

    public function getResultForDateSecond($lotteryName, $date)
    {
        throw new \BadFunctionCallException();
    }

    public function getResultBreakDownForDate($lotteryName, $date)
    {
        throw new \BadFunctionCallException();
    }

    public function getResultBreakDownForDateSecond($lotteryName, $date)
    {
        throw new \BadFunctionCallException();
    }

    public function getRaffleForDate($lotteryName, $date)
    {
        throw new \BadFunctionCallException();
    }

    public function getRaffleForDateSecond($lotteryName, $date)
    {
        throw new \BadFunctionCallException();
    }
}