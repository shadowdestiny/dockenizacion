<?php
namespace EuroMillions\controllers;

use EuroMillions\forms\GuestContactForm;
use EuroMillions\vo\ContactFormInfo;
use EuroMillions\vo\Email;

class ContactController extends PublicSiteControllerBase
{

    public function indexAction()
    {

        //EMTD: move topics like dynamic data
        $topics = [1 => 'Playing the game',
                   2 => 'Password, Email and Log in',
                   3 => 'Account settings',
                   4 => 'Bank and Credit card',
                   5 => 'Other kind of questions'
        ];

        $guestContactForm = new GuestContactForm(null, [
                'topics' => $topics
            ]
        );

        if ($this->request->isPost()) {
            if ($guestContactForm->isValid($this->request->getPost()) == false) {
                //
            } else {
                $email = $this->request->getPost('email');
                $fullName = $this->request->getPost('fullname');
                $content = $this->request->getPost('content');
                $topic   = $this->request->getPost('topic');

                $contactFormInfo  = new ContactFormInfo(new Email($email), $fullName, $content, $topic);
                $contactRequest_result = $this->userService->contactRequest($contactFormInfo);
                if($contactRequest_result->success())
                {
                    $message = $contactRequest_result->getValues();
                    $class = ' success';
                }else{
                    $message = $contactRequest_result->errorMessage();
                    $class = ' error';
                }
            }
        }
        $this->view->pick('contact/index');
        return $this->view->setVars([
            'guestContactForm'  => $guestContactForm,
            'message'     => $message,
            'class'       => $class,
        ]);

    }
}