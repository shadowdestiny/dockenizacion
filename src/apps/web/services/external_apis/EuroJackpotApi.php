<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 18/10/18
 * Time: 10:06 AM
 */

namespace EuroMillions\web\services\external_apis;

use Phalcon\Di;
use Money\Money;
use Money\Currency;
use Phalcon\Http\Client\Provider\Curl;
use EuroMillions\web\services\CurrencyConversionService;

class EuroJackpotApi extends LottorisqApi
{
    public function __construct(Curl $curlWrapper = null, CurrencyConversionService $currencyConversionService = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
        $this->config = Di::getDefault()->get('config')['eurojackpot_api'];
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
            $currency = $this->currencyConversionService->convert(new Money((int) $draw['jackpot']['total'], new Currency('USD')),
                new Currency('EUR'));
            return new Money((int) round($currency->getAmount() / 1000000) * 100000000, new Currency('EUR'));
        } catch (\Exception $e)
        {
            throw new ValidDateRangeException('The date requested ('.$date.') is not valid for the EuroJackpot');
        }
    }
}