<?php
namespace app\controllers;


use Phalcon\Config\Adapter\Ini;

class DesignTestController extends ControllerBase
{
    public function viewAction($controller, $view)
    {
        $view_variables_file = APP_PATH . 'templateHelpers/' . $controller . '/' . $view . '.ini';
        $layout_variables_file = APP_PATH . 'templateHelpers/general.ini';
        $this->setVarsFromIni($view_variables_file);
        $this->setVarsFromIni($layout_variables_file);
        $this->view->pick("$controller/$view");
    }

    /**
     * @param $variables_file
     */
    protected function setVarsFromIni($variables_file)
    {
        $values = new Ini($variables_file);
        foreach ($values->scalars as $name => $value) {
            $this->view->setVar($name, $value);
        }
        foreach ($values->arrays as $name => $value) {
            $this->view->setVar($name, json_decode($value, true));
        }
        foreach ($values->objects as $name => $value) {
            $this->view->setVar($name, json_decode($value));
        }
    }
}