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
            '2' => '19:00',
            '4' => '19:00',
            '6' => '19:00'
        ];
    }

    protected function getTimeToSub()
    {
        return '70 minutes';
    }
}