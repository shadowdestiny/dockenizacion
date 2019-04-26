<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 10/10/18
 * Time: 02:23 PM
 */

namespace EuroMillions\superenalotto\vo;

use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Money\UnknownCurrencyException;
use Phalcon\Di;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\shared\helpers\StringHelper;

class SuperEnalottoDrawBreakDown extends EuroMillionsDrawBreakDown
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
        $this->exchangeSuperEnalottoData();
    }

    protected function exchangeSuperEnalottoData()
    {

        try {
            foreach ($this->breakdown['prizes'] as $k => $data)
            {

                $methodName = $this->mappingMethodName($k);
                $euroMillionsDrawBreakDown = new SuperEnalottoDrawBreakDownData();
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
            'match-3' => 'setCategoryFive',
            'match-4' => 'setCategoryFour',
            'match-5' => 'setCategoryThree',
            'match-5-j' => 'setCategoryTwo',
            'match-6' => 'setCategoryOne'
        ];

        return $mappingArray[$key];
    }

    private function structureOfCombinations()
    {
        return [
            60 => 'category_one',
            51 => 'category_two',
            50 => 'category_three',
            40 => 'category_four',
            30 => 'category_five',
        ];
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

    public function toArray()
    {
        $categories = $this->structureOfCombinations();
        $result = [];
        foreach ($categories as $category) {
            $method = 'get' . StringHelper::fromUnderscoreToCamelCase($category);
            $data = $this->$method()->toArray();
            $data_prefixed = [];
            foreach ($data as $key => $value) {
                $data_prefixed[$category . '_' . $key] = $value;
            }
            $result = array_merge($result, $data_prefixed);
        }
        return $result;
    }

}