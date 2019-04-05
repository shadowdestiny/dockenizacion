<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 5/11/18
 * Time: 14:46
 */

namespace EuroMillions\megasena\controllers;


use EuroMillions\shared\controllers\LotteriesCartController;
use EuroMillions\web\forms\SignInForm;

class MegaSenaCartController extends LotteriesCartController
{


    public function profileAction($paramsFromPreviousAction = null)
    {
        $errors = [];
        $user = $this->authService->getCurrentUser();
        if($this->authService->isLogged()) {
            if($this->authService->getLoggedUser()->getValidated()) {
                $this->response->redirect('/'.$this->lottery.'/order');
            } else {
                $this->flash->error($this->languageService->translate('signup_emailconfirm') . '<br>'  . $this->languageService->translate('signup_emailresend'));
                $this->response->redirect('/'.$this->lottery.'/play');
            }
        }
        $sign_up_form = $this->getSignUpForm();
        list($controller, $action, $params) = $this->getPreviousParams($paramsFromPreviousAction);
        $sign_in_form = new SignInForm();
        $form_errors = $this->getErrorsArray();
        if($this->request->isPost()) {
            if ($sign_up_form->isValid($this->request->getPost()) === false) {
                $messages = $sign_up_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $result = $this->authService->registerFromCheckout([
                    'name'     => $this->request->getPost('name'),
                    'surname'  => $this->request->getPost('surname'),
                    'password' => $this->request->getPost('password'),
                    'email'    => $this->request->getPost('email'),
                    'country'  => $this->request->getPost('country'),
                    'phone_number' => $this->request->getPost('prefix')."-".$this->request->getPost('phone'),
                    'birth_date' => $this->request->getPost('year').'-'.$this->request->getPost('month').'-'.$this->request->getPost('day'),
                    'ipaddress' => !empty($this->request->getClientAddress()) ? $this->request->getClientAddress() : self::IP_DEFAULT,
                ], $user->getId());
                if($result->success()){
                    $this->flash->error($this->languageService->translate('signup_emailconfirm') . '<br>'  . $this->languageService->translate('signup_emailresend'));
                    $this->response->redirect('/'.$this->lottery.'/play');
                }else{
                    $errors [] = $result->errorMessage();
                }
            }
        }
        $this->view->pick('cart/profile');
        $this->tag->prependTitle('Log In or Sign Up');
        return $this->view->setVars([
            'which_form'  => 'up',
            'signinform'  => $sign_in_form,
            'form_errors_login' => $this->getErrorsArray(),
            'signupform'  => $sign_up_form,
            'errors'      => $errors,
            'form_errors' => $form_errors,
            'controller' => $controller,
            'action' => $action,
            'params' => json_encode($params),
        ]);
    }

    /**
     * @return array
     */
    protected function getErrorsArray()
    {
        $form_errors = [
            'email'            => '',
            'password'         => '',
            'name'             => '',
            'surname'          => '',
            'confirm_password' => '',
            'country'          => '',
            'card-number' => '',
            'card-holder' => '',
            'card-cvv' => '',
            'expiry-date-month' => '',
            'expiry-date-year' => '',
            'accept' => '',
            'day' => '',
            'month' => '',
            'year' => '',
        ];
        return $form_errors;
    }
}