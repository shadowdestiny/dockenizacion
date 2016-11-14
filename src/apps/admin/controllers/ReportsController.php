<?php

namespace EuroMillions\admin\controllers;

class ReportsController extends AdminControllerBase
{
    public function indexAction()
    {
        $this->view->pick('reports/index');
    }
}