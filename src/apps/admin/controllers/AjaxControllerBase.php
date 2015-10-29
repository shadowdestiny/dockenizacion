<?php


namespace EuroMillions\admin\controllers;


use Phalcon\Mvc\Controller;

class AjaxControllerBase extends Controller
{
    public function initialize()
    {
        parent::initialize();
        $this->noRender();
    }
}