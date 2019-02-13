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

class MegaSenaApi extends LottorisqApi
{
    public function __construct(Curl $curlWrapper = null, CurrencyConversionService $currencyConversionService = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
        $this->config = Di::getDefault()->get('config')['megasena_api'];
        $this->currencyConversionService =  $currencyConversionService ? $currencyConversionService : Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
    }

}