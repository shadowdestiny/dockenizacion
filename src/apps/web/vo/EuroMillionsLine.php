<?php
namespace EuroMillions\web\vo;


use ReflectionObject;
use ReflectionProperty;

class EuroMillionsLine
{
    const NUM_REGULAR_NUMBERS = 5;
    const NUM_LUCKY_NUMBERS = 2;

    private $regular_numbers;
    private $lucky_numbers;

    protected $regular_number_one;
    protected $regular_number_two;
    protected $regular_number_three;
    protected $regular_number_four;
    protected $regular_number_five;

    protected $lucky_number_one;
    protected $lucky_number_two;


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
        $this->setPropertiesValues($regular_numbers, $lucky_numbers);
    }

    private function setPropertiesValues(array $regular_numbers, array $lucky_numbers)
    {
        sort($regular_numbers);
        sort($lucky_numbers);
        $this->regular_number_one = $regular_numbers[0]->getNumber();
        $this->regular_number_two = $regular_numbers[1]->getNumber();
        $this->regular_number_three = $regular_numbers[2]->getNumber();
        $this->regular_number_four = $regular_numbers[3]->getNumber();
        $this->regular_number_five = $regular_numbers[4]->getNumber();
        $this->lucky_number_one = $lucky_numbers[0]->getNumber();
        $this->lucky_number_two = $lucky_numbers[1]->getNumber();

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
            if (!is_a($number, 'EuroMillions\web\vo\\'.$type)) {
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
        $numbers = $this->getRegularNumbersArray();
        $this->regular_numbers = implode(',', $numbers);
        return $this->regular_numbers;
    }

    public function getLuckyNumbers()
    {
        $lucky = $this->getLuckyNumbersArray();
        $this->lucky_numbers = implode(',', $lucky);
        return $this->lucky_numbers;
    }

    /**
     * @return array
     */
    public function getRegularNumbersArray()
    {
        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PROTECTED);
        $numbers = [];
        foreach ($properties as $property) {
            $name = $property->getName();
            if (substr($name, 0, 7) === 'regular') {
                $numbers[] = $this->$name;
            }
        }
        return $numbers;
    }

    /**
     * @return array
     */
    public function getLuckyNumbersArray()
    {
        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PROTECTED);
        $lucky = [];
        foreach ($properties as $property) {
            $name = $property->getName();
            if (substr($name, 0, 5) === 'lucky') {
                $lucky[] = $this->$name;
            }
        }
        return $lucky;
    }

}