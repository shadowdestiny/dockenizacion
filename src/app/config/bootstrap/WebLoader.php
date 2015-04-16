<?php
namespace app\config\bootstrap;
use Phalcon;
require_once 'LoaderBase.php';
class WebLoader extends LoaderBase
{
    protected function getSpecificNamespaces()
    {
        return [
            'app\controllers'   => $this->appPath . 'controllers',
        ];
    }
}