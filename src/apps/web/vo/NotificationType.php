<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\exceptions\InvalidNotificationException;


class NotificationType
{

    const NOTIFICATION_THRESHOLD = 1;

    const NOTIFICATION_NOT_ENOUGH_FUNDS = 2;

    const NOTIFICATION_LAST_DRAW = 3;

    const NOTIFICATION_RESULT_DRAW = 4;

    protected $type;

    protected $value;

    public function __construct($type,$value)
    {
        if(!$this->checkValueOfType($type,$value)) {
            throw new InvalidNotificationException;
        }else{
            $this->type = $type;
            $this->value = $value;
        }
    }

    private function checkValueOfType($type,$value)
    {
        $result = false;

        switch($type) {
            case self::NOTIFICATION_THRESHOLD:
                $result = (is_int($value)) ? true : false;
                break;
            case self::NOTIFICATION_NOT_ENOUGH_FUNDS:
                $result = true;
                break;
            case self::NOTIFICATION_LAST_DRAW:
                $result = true;
                break;
            case self::NOTIFICATION_RESULT_DRAW:
                $result = (is_bool((bool) $value)) ? true : false;
                break;
            default:
                throw new InvalidNotificationException;
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