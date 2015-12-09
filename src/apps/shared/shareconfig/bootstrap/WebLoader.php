<?php
namespace EuroMillions\shared\shareconfig\bootstrap;
use Phalcon;
require_once 'LoaderBase.php';
class WebLoader extends LoaderBase
{
    protected function getSpecificNamespaces()
    {
        return [
            'EuroMillions\web\controllers'   => $this->appPath . 'web\controllers',
            'EuroMillions\admin\controllers'   => $this->appPath . 'admin\controllers',
        ];
    }
}