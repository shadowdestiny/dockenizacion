<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 12/11/18
 * Time: 10:54
 */

namespace EuroMillions\web\vo\enum;


class MoneyMatrixStatusCode extends \SplEnum
{

    const SUCCESS = 1;

    const ERROR = 2;

    const PENDING_NOTIFICATION = 3;

    const PENDING_CONFIRMATION = 4;

    const REJECTED = 5;

    const CANCELED = 6;

    const VOIDED = 7;

    const PENDING_APPROVAL = 8;

    const AUTHORIZED = 9;


    public function getValue($key)
    {
        $declaredElems = $this->getConstList();
        if(array_key_exists($key, $declaredElems)){
            try {
                $r = new \ReflectionClass($this);
            } catch (\ReflectionException $e) {
            }
            return $r->getConstant($key);
        }else{
            return self::__default;
        }
    }

}