<?php
namespace EuroMillions\controllers;

use EuroMillions\forms\GuestContactForm;
use EuroMillions\vo\ContactFormInfo;
use EuroMillions\vo\Email;

class ContactController extends PublicSiteControllerBase
{
    public function guestAction()
    {
        $errors = null;

        $topics = ['Playing the game',
                   'fdsfdsf',
                   'dsfsdfsd'
        ];

        //TODO: Perhaps move topic like a dynamic data
        $guestContactForm = new GuestContactForm(null, ['topics' => $topics]);

        if ($this->request->isPost()) {
            if ($guestContactForm->isValid($this->request->getPost()) == false) {
                $messages = $guestContactForm->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
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
            'errors'      => $errors,
        ]);
    }

    public function registeredAction()
    {
        //EMTD to be completed by Raul
    }
}