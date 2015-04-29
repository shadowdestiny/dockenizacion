<?php
namespace app\controllers;

use antonienko\PhpTempPrev\FrameworkStrategies\PhalconStrategy;
use antonienko\PhpTempPrev\Previewer;

class DesignTestController extends ControllerBase
{
    public function viewAction($controller, $view)
    {
        $view_variables_file = APP_PATH . 'templateHelpers/' . $controller . '/' . $view . '.ini';
        $layout_variables_file = APP_PATH . 'templateHelpers/general.ini';
        $previewer = new Previewer(new PhalconStrategy($this->view));
        $previewer->render("$controller/$view", [$layout_variables_file, $view_variables_file]);
    }
}