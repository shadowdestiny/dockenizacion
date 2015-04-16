<?php
namespace app\config\bootstrap;

require_once 'LoaderBase.php';
class CliLoader extends LoaderBase
{
    protected function getSpecificNamespaces()
    {
        return [
            'app\tasks' => $this->appPath . 'tasks',
        ];
    }
}