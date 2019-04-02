<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 22/03/19
 * Time: 10:35 AM
 */

namespace EuroMillions\web\vo\cutoff;

class EuroMillionsCutOff extends CutOff
{
    protected function getCloseTime()
    {
        return [
            '2' => '20:00',
            '5' => '20:00'
        ];
    }

    protected function getTimeToSub()
    {
        return '70 minutes';
    }
}