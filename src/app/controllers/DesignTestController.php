<?php
namespace EuroMillions\controllers;

use antonienko\PhpTempPrev\FileStrategies\JsonFileStrategy;
use antonienko\PhpTempPrev\FrameworkStrategies\PhalconStrategy;
use antonienko\PhpTempPrev\Previewer;

class DesignTestController extends PublicSiteControllerBase
{
    public function viewAction($controller, $view)
    {
        $view_variables_file = APP_PATH . 'templateHelpers/' . $controller . '/' . $view . '.json';
        $layout_variables_file = APP_PATH . 'templateHelpers/general.json';
        $previewer = new Previewer(new PhalconStrategy($this->view));
        $previewer->render("$controller/$view", new JsonFileStrategy([$layout_variables_file, $view_variables_file]));
    }
}