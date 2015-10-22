<?php


namespace EuroMillions\vo\dto;


use EuroMillions\interfaces\IDto;
use EuroMillions\services\CurrencyService;
use EuroMillions\vo\dto\base\DTOBase;
use EuroMillions\vo\EuroMillionsDrawBreakDown;
use Money\Currency;
use Money\Money;
use ReflectionObject;
use ReflectionProperty;

class EuroMillionsDrawBreakDownDTO extends DTOBase implements IDto
{

    private $euroMillionsDrawBreakDown;

    public $category_one;

    public $category_two;

    public $category_three;

    public $category_four;

    public $category_five;

    public $category_six;

    public $category_seven;

    public $category_eight;

    public $category_nine;

    public $category_ten;

    public $category_eleven;

    public $category_twelve;

    public $category_thirteen;

    /** @var CurrencyService  */
    private $currencyService;


    public function __construct(EuroMillionsDrawBreakDown $euroMillionsDrawBreakDown)
    {
        $this->euroMillionsDrawBreakDown=$euroMillionsDrawBreakDown;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            $name    = $property->getName();
            $nameMethod = 'get'.str_replace("_","",ucwords($name,'_'));
            $this->{$name} = new EuroMillionsDrawBreakDownDataDTO($this->euroMillionsDrawBreakDown->$nameMethod());
        }
    }

    public function convertCurrency(Currency $currency)
    {
        $reflection = new ReflectionObject($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            $name    = $property->getName();
            $this->$name()->setLotteryPrize($this->convert($this->$name()->getLotteryPrize(), $currency));
        }
    }

    public function toArray()
    {
        return $array = json_decode(json_encode($this),TRUE);
    }

    public function toJson()
    {
        return json_encode(json_decode(json_encode($this),TRUE));
    }

    private function convert($prize, Currency $currency)
    {
        $currency_prize = $this->currencyService->convert(new Money((int) $prize, new Currency('EUR')), $currency)->getAmount() / 10000;
        return $currency_prize;
    }

}