<?php
/**
 * Created by PhpStorm.
 * User: wilcarjose
 * Date: 13/02/19
 * Time: 13:36
 */

namespace EuroMillions\web\services\external_apis;

use Phalcon\Di;
use Phalcon\Http\Client\Provider\Curl;
use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use EuroMillions\web\exceptions\ValidDateRangeException;
use Money\Money;

class MegaSenaApi extends LottorisqApi
{
    public function __construct(Curl $curlWrapper = null, CurrencyConversionService $currencyConversionService = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
        $this->config = Di::getDefault()->get('config')['megasena_api'];
        $this->currencyConversionService =  $currencyConversionService ? $currencyConversionService : Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
    }

    public function getJackpotForDate($lotteryName, $date = null)
    {
        if (!$date) {
            $date = (new \DateTime())->format('Y-m-d');
        }
        try {
            $drawBody = $this->sendCurl('/results'.'/'.$date);
            $draw = json_decode($drawBody, true);
            $currency = $this->currencyConversionService->convert(new Money((int) $draw['jackpot']['total'], new Currency('BRL')),
                new Currency('EUR'));
            $amount = (($currency->getAmount() / 1000000) < 1) ?
                ((int) floor($currency->getAmount() / 100000)  * 10000000) :
                ((int) floor($currency->getAmount() / 1000000) * 100000000);
            return new Money($amount, new Currency('EUR'));
        } catch (\Exception $e)
        {
            throw new ValidDateRangeException('The date requested ('.$date.') is not valid for the EuroJackpot');
        }
    }
}