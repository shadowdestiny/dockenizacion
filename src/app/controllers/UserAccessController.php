<?php
namespace EuroMillions\controllers;

class UserAccessController extends ControllerBase
{
    public function signInAction()
    {
        $this->view->pick('sign-in/index');
    }
}