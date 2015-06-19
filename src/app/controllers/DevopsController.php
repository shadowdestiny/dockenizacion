<?php
namespace EuroMillions\controllers;

class DevopsController extends ControllerBase
{
    public function clearApcAction()
    {
        $this->noRender();
        apc_clear_cache();
        apc_clear_cache('user');
        echo 'Cache cleared';
    }
}