<?php
namespace EuroMillions\config\bootstrap;

require_once 'LoaderBase.php';
class CliLoader extends LoaderBase
{
    protected function getSpecificNamespaces()
    {
        return [
            'EuroMillions\tasks' => $this->appPath . 'tasks',
        ];
    }
}