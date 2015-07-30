<?php
namespace EuroMillions\controllers;

use EuroMillions\forms\SignInForm;

class UserAccessController extends ControllerBase
{
    public function signInAction()
    {
        $form = new SignInForm();
        if (!$this->request->isPost()) {
            $this->noRender();
            var_dump('nopost');
        } else {
            $this->noRender();
            var_dump('post');
        }
        $this->view->setVar('form', $form);
        $this->view->pick('sign-in/index');
    }
}