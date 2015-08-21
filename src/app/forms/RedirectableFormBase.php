<?php
namespace EuroMillions\forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Form;

class RedirectableFormBase extends Form
{
    public function initialize()
    {
        $controller = new Hidden('controller');
        $this->add($controller);
        $action = new Hidden('action');
        $this->add($action);
        $params = new Hidden('params');
        $this->add($params);
    }
}