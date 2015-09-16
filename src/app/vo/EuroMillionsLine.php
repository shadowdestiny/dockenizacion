<?php
namespace EuroMillions\vo;

class EuroMillionsLine
{
    const NUM_REGULAR_NUMBERS = 5;
    const NUM_LUCKY_NUMBERS = 2;

    protected $regular_numbers;
    protected $lucky_numbers;

    /**
     * @param EuroMillionsRegularNumber[] $regular_numbers
     * @param EuroMillionsLuckyNumber[] $lucky_numbers
     */
    public function __construct(array $regular_numbers, array $lucky_numbers)
    {
        if (count($regular_numbers) != self::NUM_REGULAR_NUMBERS || count($lucky_numbers) != self::NUM_LUCKY_NUMBERS ){
            throw new \InvalidArgumentException("An EuroMillions result should have ".self::NUM_REGULAR_NUMBERS." regular numbers and ".self::NUM_LUCKY_NUMBERS." lucky numbers");
        }
        if ($this->checkTypeAndRepeated($regular_numbers, 'EuroMillionsRegularNumber') || $this->checkTypeAndRepeated($lucky_numbers, 'EuroMillionsLuckyNumber')) {
            throw new \InvalidArgumentException("The result numbers cannot have duplicates");
        }
        $callback = function (EuroMillionsResultNumber $elem) {
            return $elem->getNumber();
        };
        $this->regular_numbers = implode(',',array_map($callback, $regular_numbers));
        $this->lucky_numbers = implode(',',array_map($callback, $lucky_numbers));
    }

    /**
     * @param EuroMillionsResultNumber[] $numbers
     * @param $type
     * @return bool
     */
    private function checkTypeAndRepeated($numbers, $type)
    {
        $existing_numbers = [];
        foreach ($numbers as $number) {
            if (!is_a($number, 'EuroMillions\vo\\'.$type)) {
                throw new \InvalidArgumentException("The numbers should be proper value objects");
            }
            if (isset($existing_numbers[$number->getNumber()])) {
                return true;
            }
            $existing_numbers[$number->getNumber()] = true;
        }
        return false;
    }

    public function getRegularNumbers()
    {
        return $this->regular_numbers;
    }

    public function getLuckyNumbers()
    {
        return $this->lucky_numbers;
    }

    public function setLuckyNumbers($luckyNumbers)
    {
        $this->lucky_numbers = $luckyNumbers;
    }

    public function setRegularNumbers($regularNumbers)
    {
        $this->regular_numbers = $regularNumbers;
    }
}