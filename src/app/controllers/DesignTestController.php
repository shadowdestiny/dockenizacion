<?php
namespace app\controllers;


use Phalcon\Config\Adapter\Ini;

class DesignTestController extends ControllerBase
{
    public function viewAction($controller, $view)
    {
        $variables_file = APP_PATH . 'templateHelpers/' . $controller . '/' . $view . '.ini';
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
        $this->view->pick("$controller/$view");
    }
}