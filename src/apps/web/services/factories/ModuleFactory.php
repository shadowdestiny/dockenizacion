<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 06/02/19
 * Time: 12:26 PM
 */

namespace EuroMillions\web\services\factories;

use EuroMillions\shared\config\bootstrap\modules\WebModule;
use EuroMillions\shared\config\bootstrap\modules\AdminModule;
use EuroMillions\shared\config\bootstrap\modules\MegaSenaModule;
use EuroMillions\shared\config\bootstrap\modules\EuroJackpotModule;
use EuroMillions\shared\config\bootstrap\modules\MegaMillionsModule;
use EuroMillions\shared\config\bootstrap\modules\SuperEnalottoModule;

class ModuleFactory
{
    public static function create($name, $application, $di, $appPath, $assetsPath, $diView)
    {
        if ($name === 'web') {
            return new WebModule($name, $application, $di, $appPath, $assetsPath, $diView);
        }

        if ($name === 'admin') {
            return new AdminModule($name, $application, $di, $appPath, $assetsPath, $diView);
        }

        if ($name === 'megamillions') {
            return new MegaMillionsModule($name, $application, $di, $appPath, $assetsPath, $diView);
        }

        if ($name === 'eurojackpot') {
            return new EuroJackpotModule($name, $application, $di, $appPath, $assetsPath, $diView);
        }

        if ($name === 'megasena') {
            return new MegaSenaModule($name, $application, $di, $appPath, $assetsPath, $diView);
        }

        if ($name === 'superenalotto') {
            return new SuperEnalottoModule($name, $application, $di, $appPath, $assetsPath, $diView);
        }
    }
}