<?php
namespace EuroMillions\shared\shareconfig\bootstrap;

require_once 'LoaderBase.php';
class CliLoader extends LoaderBase
{
    protected function getSpecificNamespaces()
    {
        return [
            'EuroMillions\web\tasks' => $this->appPath . 'web/tasks',
        ];
    }
}