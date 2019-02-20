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
use EuroMillions\shared\helpers\StringHelper;

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
                        new Currency('EUR'))
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
            'match-2-1' => 'setCategoryTwelve',
            'match-1-2' => 'setCategoryEleven',
            'match-3' => 'setCategoryTen',
            'match-3-1' => 'setCategoryNine',
            'match-2-2' => 'setCategoryEight',
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
            return new Money((int) $moneyConverted->getAmount(), new Currency('EUR'));
        } catch (UnknownCurrencyException $e) {
            return null;
        }
    }

    private function structureOfCombinations()
    {
        return [
            52 => 'category_one',
            51 => 'category_two',
            50 => 'category_three',
            42 => 'category_four',
            41 => 'category_five',
            40 => 'category_six',
            32 => 'category_seven',
            22 => 'category_eight',
            31 => 'category_nine',
            30 => 'category_ten',
            12 => 'category_eleven',
            21 => 'category_twelve',
        ];
    }

    public function toArray()
    {
        $categories = $this->structureOfCombinations();
        $result = [];
        foreach ($categories as $category) {
            $method = 'get' . StringHelper::fromUnderscoreToCamelCase($category);
            $category_result = $this->$method();
            if (is_null($category_result)) {
                continue;
            }
            $data = $category_result->toArray();
            $data_prefixed = [];
            foreach ($data as $key => $value) {
                $data_prefixed[$category . '_' . $key] = $value;
            }
            $result = array_merge($result, $data_prefixed);
        }
        return $result;
    }

}