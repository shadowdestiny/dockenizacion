<?php


namespace EuroMillions\admin\controllers;


use antonienko\PhpTempPrev\FileStrategies\JsonFileStrategy;
use antonienko\PhpTempPrev\FrameworkStrategies\PhalconStrategy;
use antonienko\PhpTempPrev\Previewer;
use Phalcon\Mvc\Controller;

class DesignController extends Controller
{

    public function viewAction($controller, $view)
    {
        $view_variables_file = APP_PATH . 'admin/templateHelpers/' . $controller . '/' . $view . '.json';
        $layout_variables_file = APP_PATH . 'admin/templateHelpers/general.json';
        $previewer = new Previewer(new PhalconStrategy($this->view));
        $previewer->render("$controller/$view", new JsonFileStrategy([$layout_variables_file, $view_variables_file]));
    }

}