<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use ReflectionObject;
use ReflectionProperty;

class EuroMillionsDrawBreakDownDTO extends DTOBase implements IDto
{

    protected $euroMillionsDrawBreakDown;

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

    public function toArray()
    {
        return json_decode(json_encode($this),TRUE);
    }

    public function toJson()
    {
        return json_encode(json_decode(json_encode($this),TRUE));
    }

}