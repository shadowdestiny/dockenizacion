<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 28/03/19
 * Time: 04:33 PM
 */

namespace EuroMillions\shared\config\bootstrap\modules;


class MegaMillionsModule extends Module
{
    protected function getViewDir()
    {
        return 'megamillions/views/';
    }
}