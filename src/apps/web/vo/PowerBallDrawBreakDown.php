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
            'match-5-pp' => 'setCategorySeventeen',
            'match-5-p' => 'setCategorySixteen',
            'match-5' => 'setCategoryFifteen',
            'match-4-pp' => 'setCategoryFourteen',
            'match-4-p-pp' => 'setCategoryThirteen',
            'match-4-p' => 'setCategoryTwelve',
            'match-4' => 'setCategoryEleven',
            'match-3-pp' => 'setCategoryTen',
            'match-3-p-pp' => 'setCategoryNine',
            'match-3-p' => 'setCategoryEight',
            'match-3' => 'setCategorySeven',
            'match-2-p-pp' => 'setCategorySix',
            'match-2-p' => 'setCategoryFive',
            'match-1-p-pp' => 'setCategoryFour',
            'match-1-p' => 'setCategoryThree',
            'match-0-p-pp' => 'setCategoryTwo',
            'match-0-p' => 'setCategoryOne'
        ];

        return $mappingArray[$key];
    }

    protected function currencyConversion(Money $money)
    {
        try {
            /** @var CurrencyConversionService $currencyConversion */
            $currencyConversion = Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
            return $currencyConversion->convert($money, new Currency('EUR'));
        } catch (UnknownCurrencyException $e) {
            return null;
        }
    }





}