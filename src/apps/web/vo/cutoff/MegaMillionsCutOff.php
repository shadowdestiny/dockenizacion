<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 22/03/19
 * Time: 10:35 AM
 */

namespace EuroMillions\web\vo\cutoff;

class MegaMillionsCutOff extends CutOff
{
    protected function getCloseTime()
    {
        return [
            '3' => '04:00',
            '6' => '04:00'
        ];
    }

    protected function getTimeToSub()
    {
        return '70 minutes';
    }
}