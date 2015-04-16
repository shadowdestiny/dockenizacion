<?php
namespace app\controllers;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    protected function noRender()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
    }
}
