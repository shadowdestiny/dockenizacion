<?php

namespace EuroMillions\admin\controllers;

class ApiControllerBase extends AdminControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->disable();
        header('Content-type:application/json;charset=utf-8');
    }

    public function sendJson($values, $page = null)
    {
        echo json_encode([
            'result'=> 'Ok',
            'data' => $values,
            'page'  => $page
        ]);
    }

    public function sendError($error)
    {
        echo json_encode([
            'result'=> 'Error',
            'error' => $error
        ]);
    }
}