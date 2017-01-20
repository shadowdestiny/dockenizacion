<?php

namespace EuroMillions\admin\controllers;

class TrackingController extends AdminControllerBase
{
    public function indexAction()
    {
        return $this->view->setVars([
            'hola' => 'hola',
        ]);
    }
}