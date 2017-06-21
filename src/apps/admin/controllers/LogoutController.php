<?php

namespace EuroMillions\admin\controllers;

class LogoutController extends AdminControllerBase
{
    public function indexAction()
    {
        $this->session->destroy();
    }
}
