<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 18/10/18
 * Time: 10:06 AM
 */

namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\services\CurrencyConversionService;
use Phalcon\Di;
use Phalcon\Http\Client\Provider\Curl;

class MegaMillionsApi extends LottorisqApi
{
    public function __construct(Curl $curlWrapper = null, CurrencyConversionService $currencyConversionService = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
        $this->config = Di::getDefault()->get('config')['megamillions_api'];
        $this->currencyConversionService =  $currencyConversionService ? $currencyConversionService : Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
    }

}