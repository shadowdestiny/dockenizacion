<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 10/10/18
 * Time: 02:23 PM
 */

namespace EuroMillions\eurojackpot\vo;

use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Money\UnknownCurrencyException;
use Phalcon\Di;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;

class EuroJackpotDrawBreakDown extends EuroMillionsDrawBreakDown
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
        $this->exchangeEuroJackpotData();
    }

    protected function exchangeEuroJackpotData()
    {

        try {
            foreach ($this->breakdown['prizes'] as $k => $data)
            {

                $methodName = $this->mappingMethodName($k);
                $euroMillionsDrawBreakDown = new EuroJackpotDrawBreakDownData();
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
            'match-1-2' => 'setCategoryTwelve',
            'match-2-1' => 'setCategoryEleven',
            'match-2-2' => 'setCategoryTen',
            'match-3' => 'setCategoryNine',
            'match-3-1' => 'setCategoryEight',
            'match-3-2' => 'setCategorySeven',
            'match-4' => 'setCategorySix',
            'match-4-1' => 'setCategoryFive',
            'match-4-2' => 'setCategoryFour',
            'match-5' => 'setCategoryThree',
            'match-5-1' => 'setCategoryTwo',
            'match-5-2' => 'setCategoryOne'
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