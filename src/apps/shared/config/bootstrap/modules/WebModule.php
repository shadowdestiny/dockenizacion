<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 28/03/19
 * Time: 04:32 PM
 */

namespace EuroMillions\shared\config\bootstrap\modules;


class WebModule extends Module
{
    protected function getViewDir()
    {
        return 'web/views/';
    }
}