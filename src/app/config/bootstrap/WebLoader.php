<?php
namespace EuroMillions\config\bootstrap;
use Phalcon;
require_once 'LoaderBase.php';
class WebLoader extends LoaderBase
{
    protected function getSpecificNamespaces()
    {
        return [
            'EuroMillions\controllers'   => $this->appPath . 'controllers',
        ];
    }
}