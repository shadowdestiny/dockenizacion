<?php


namespace EuroMillions\admin\controllers;

class AjaxControllerBase extends AdminControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->noRender();
    }
}