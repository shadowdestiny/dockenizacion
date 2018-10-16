<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 10/10/18
 * Time: 02:23 PM
 */

namespace EuroMillions\web\vo;

use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Money\UnknownCurrencyException;
use Phalcon\Di;

class MegaMillionsDrawBreakDown extends EuroMillionsDrawBreakDown
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
        $this->exchangeMegaMillionsData();
    }

    protected function exchangeMegaMillionsData()
    {

        try {
            foreach ($this->breakdown['prizes'] as $k => $data)
            {

                $methodName = $this->mappingMethodName($k);
                $euroMillionsDrawBreakDown = new MegaMillionsDrawBreakDownData();
                $euroMillionsDrawBreakDown->setName($k);

                $euroMillionsDrawBreakDown->setLotteryPrize($this->currencyConversion(
                    new Money((int) str_replace('.','', $data),
                        new Currency('USD'))
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
            'match-0-m' => 'setCategorySeventeen',
            'match-0-m-mp' => 'setCategorySixteen',
            'match-1-m' => 'setCategoryFifteen',
            'match-1-m-mp' => 'setCategoryFourteen',
            'match-2-m' => 'setCategoryThirteen',
            'match-2-m-mp' => 'setCategoryTwelve',
            'match-3' => 'setCategoryEleven',
            'match-3-m' => 'setCategoryTen',
            'match-3-m-mp' => 'setCategoryNine',
            'match-3-mp' => 'setCategoryEight',
            'match-4' => 'setCategorySeven',
            'match-4-m' => 'setCategorySix',
            'match-4-m-mp' => 'setCategoryFive',
            'match-4-mp' => 'setCategoryFour',
            'match-5' => 'setCategoryThree',
            'match-5-m' => 'setCategoryTwo',
            'match-5-mp' => 'setCategoryOne'
        ];

        return $mappingArray[$key];
    }

    protected function currencyConversion(Money $money)
    {
        try {
            /** @var CurrencyConversionService $currencyConversion */
            $currencyConversion = Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
            $moneyConverted = $currencyConversion->convert($money, new Currency('EUR'));
            return new Money((int) $moneyConverted->getAmount() * 100, new Currency('EUR'));
        } catch (UnknownCurrencyException $e) {
            return null;
        }
    }
}