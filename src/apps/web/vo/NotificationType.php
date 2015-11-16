<?php


namespace EuroMillions\web\vo;


class NotificationType
{

    protected $type;

    protected $value;

    public function __construct($type,$value)
    {
        $this->type = $type;
        $this->value = $value;
        if(!$this->checkValueOfType()) {
            throw new \Exception();
        }
    }

    private function checkValueOfType()
    {

        $result = false;
        switch($this->type) {
            case 1:
                $result = (is_int((int) $this->value)) ? true : false;
                break;
            case 2:
                break;
            case 3:
                break;
            case 4:
                $result = (is_bool((bool) $this->value)) ? true : false;
                break;
            default:
                throw new \Exception();
        }
        return $result;
    }


    public function getValue()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function getType()
    {
        return $this->type;
    }

}