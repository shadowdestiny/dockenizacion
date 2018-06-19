<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 15/06/18
 * Time: 10:56
 */

namespace EuroMillions\web\vo;


use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Money\UnknownCurrencyException;
use Phalcon\Di;

class PowerBallDrawBreakDown extends EuroMillionsDrawBreakDown
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
        $this->exchangePowerBallData();
    }

    protected function exchangePowerBallData()
    {

        try {
            foreach ($this->breakdown['prizes'] as $k => $data)
            {

                $methodName = $this->mappingMethodName($k);
                $euroMillionsDrawBreakDown = new PowerBallDrawBreakDownData();
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
                'match-0-p' => 'setCategorySeventeen',
                'match-0-p-pp' => 'setCategorySixteen',
                'match-1-p' => 'setCategoryFifteen',
                'match-1-p-pp' => 'setCategoryFourteen',
                'match-2-p' => 'setCategoryThirteen',
                'match-2-p-pp' => 'setCategoryTwelve',
                'match-3' => 'setCategoryEleven',
                'match-3-p' => 'setCategoryTen',
                'match-3-p-pp' => 'setCategoryNine',
                'match-3-pp' => 'setCategoryEight',
                'match-4' => 'setCategorySeven',
                'match-4-p' => 'setCategorySix',
                'match-4-p-pp' => 'setCategoryFive',
                'match-4-pp' => 'setCategoryFour',
                'match-5' => 'setCategoryThree',
                'match-5-p' => 'setCategoryTwo',
                'match-5-pp' => 'setCategoryOne'
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