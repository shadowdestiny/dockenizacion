<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 23/04/19
 * Time: 11:08 PM
 */

namespace EuroMillions\web\vo\cutoff;

class SuperEnalottoCutOff extends CutOff
{
    protected function getCloseTime()
    {
        return [
            '3' => '16:00',
            '6' => '09:00'
        ];
    }

    protected function getTimeToSub()
    {
        return '70 minutes';
    }
}