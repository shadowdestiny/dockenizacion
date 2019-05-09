<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 22/03/19
 * Time: 10:35 AM
 */

namespace EuroMillions\web\vo\cutoff;

class PowerBallCutOff extends CutOff
{
    protected function getCloseTime()
    {
        return [
            '4' => '04:30',
            '0' => '04:30'
        ];
    }

    protected function getTimeToSub()
    {
        return '70 minutes';
    }
}