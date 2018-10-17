<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\GuestContactForm;
use EuroMillions\web\vo\ContactFormInfo;
use EuroMillions\web\vo\Email;
use Phalcon\Forms\Element\Text;
use Captcha\Captcha;
use EuroMillions\web\components\ReCaptchaWrapper;

class ContactController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $errors = [];
        $form_errors = $this->getErrorsArray();
        $email = $this->request->getPost('email');
        $fullName = $this->request->getPost('fullname');
        $content = $this->request->getPost('message');
        $topic = $this->request->getPost('topic');
        $message = null;
        $class = null;
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
        //get captcha instance
        $config = $this->di->get('config')['recaptcha'];
        $captcha = new ReCaptchaWrapper(new Captcha());
        $captcha->getCaptcha()->setPublicKey($config['public_key']);
        $captcha->getCaptcha()->setPrivateKey($config['private_key']);
        /** @var User $user */
        $user = $this->authService->getCurrentUser();

        $contactRequest_result = null;
        if ($this->request->isPost()) {
            if(!$user instanceof User) {
                if ($guestContactForm->isValid($this->request->getPost()) == false ) {
                    list($errors, $form_errors) = $this->messageErrors($guestContactForm, $errors, $form_errors);
                } else {
                    list($class, $errors, $message) = $this->checker($captcha, $email, $fullName, $content, $topic, $errors, $guestContactForm, $contactRequest_result);
                }
            } else {
                if ($user instanceof User) {
                    $email = $user->getEmail()->toNative();
                    $fullName = $user->getName() . ' ' . $user->getSurname();
                }
                $_POST['email'] = $email;
                $_POST['fullname'] = $fullName;
                if ($guestContactForm->isValid($this->request->getPost()) == false ) {
                    list($errors, $form_errors) = $this->messageErrors($guestContactForm, $errors, $form_errors);
                } else {
                    list($class, $errors, $message) = $this->checker($captcha, $email, $fullName, $content, $topic, $errors, $guestContactForm, $contactRequest_result);
                }
            }
        }
        $this->view->pick('contact/index');

        $this->tag->prependTitle($this->languageService->translate('contact_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('contact_desc'));

        return $this->view->setVars([
            'form_errors' => $form_errors,
            'errors' => $errors,
            'guestContactForm' => $guestContactForm,
            'message' => $message,
            'class' => $class,
            'captcha' => $captcha->html(),
            'pageController' => 'contact',
        ]);
    }

    /**
     * @return array
     */
    private function getErrorsArray()
    {
        $form_errors = [
            'fullname' => '',
            'email' => '',
            'message' => '',
            'accept' => ''
        ];
        return $form_errors;
    }

    /**
     * @param $captcha
     * @param $email
     * @param $fullName
     * @param $content
     * @param $topic
     * @param $errors
     * @param $guestContactForm
     * @param $contactRequest_result
     * @return array
     */
    //TODO: decrease params numbers. Phpstorm extract method
    public function checker($captcha,
                            $email,
                            $fullName,
                            $content,
                            $topic,
                            $errors,
                            $guestContactForm,
                            $contactRequest_result)
    {
        $reCaptchaResult = $captcha->check()->isValid();
        $contactFormInfo = new ContactFormInfo(new Email($email), $fullName, $content, $topic);
        $class = ' error';
        if (empty($reCaptchaResult)) {

            $errors[] = 'You are a robot... or you forgot to check the Captcha verification.';
        } elseif ($reCaptchaResult) {
            $contactRequest_result = $this->userService->contactRequest($contactFormInfo);
            if ($contactRequest_result->success()) {
                $guestContactForm->clear();
                $message = $contactRequest_result->getValues();
                $class = ' success';
            }
        } else {
            $errors[] = $contactRequest_result->errorMessage();
        }
        return array($class, $errors, $message);
    }

    /**
     * @param $guestContactForm
     * @param $errors
     * @param $form_errors
     * @return array
     */
    public function messageErrors($guestContactForm, $errors, $form_errors)
    {
        $messages = $guestContactForm->getMessages(true);
        /**
         * @var string $field
         * @var Message\Group $field_messages
         */
        foreach ($messages as $field => $field_messages) {
            $errors[] = $field_messages[0]->getMessage();
            $form_errors[$field] = ' error';
        }
        return array($errors, $form_errors);
    }
}
