<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 10/10/18
 * Time: 02:23 PM
 */

namespace EuroMillions\megasena\vo;

use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Money\UnknownCurrencyException;
use Phalcon\Di;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;

class MegaSenaDrawBreakDown extends EuroMillionsDrawBreakDown
{
    /** @var CurrencyConversionService $currencyConversion */
    protected $currencyConversion;
    /**
     * @param array $breakdown
     */
    public function __construct(array $breakdown)
    {
        if (!is_array($breakdown)) {
            throw new \InvalidArgumentException("");
        }
        $this->breakdown = $breakdown;
        $this->exchangeMegaSenaData();
    }

    protected function exchangeMegaSenaData()
    {

        try {
            foreach ($this->breakdown['prizes'] as $k => $data)
            {

                $methodName = $this->mappingMethodName($k);
                $euroMillionsDrawBreakDown = new MegaSenaDrawBreakDownData();
                $euroMillionsDrawBreakDown->setName($k);

                $euroMillionsDrawBreakDown->setLotteryPrize($this->currencyConversion(
                    new Money((int) str_replace('.','', $data),
                        new Currency('BRL'))
                )
                );
                $euroMillionsDrawBreakDown->setWinners($this->breakdown['winners'][$k]);
                $this->$methodName($euroMillionsDrawBreakDown);
            }
        } catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    private function mappingMethodName($key)
    {
        $mappingArray = [
            'match-4' => 'setCategoryThree',
            'match-5' => 'setCategoryTwo',
            'match-6' => 'setCategoryOne'
        ];

        return $mappingArray[$key];
    }

    protected function currencyConversion(Money $money)
    {
        try {
            /** @var CurrencyConversionService $currencyConversion */
            $currencyConversion = Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
            $moneyConverted = $currencyConversion->convert($money, new Currency('EUR'));
            return new Money((int) $moneyConverted->getAmount(), new Currency('EUR'));
        } catch (UnknownCurrencyException $e) {
            return null;
        }
    }

}