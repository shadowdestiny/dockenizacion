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
        $topic   = $this->request->getPost('topic');
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
        if ($this->request->isPost()) {
            if ($guestContactForm->isValid($this->request->getPost()) == false && !$user) {
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
                if ($user instanceof User) {
                    $email = $user->getEmail()->toNative();
                    $fullName = $user->getName() . ' ' . $user->getSurname();
                }
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
            }
        }
        $this->view->pick('contact/index');

	    $this->tag->prependTitle('Contact Us');
	    MetaDescriptionTag::setDescription('Contact the official EuroMillions.com. We are always happy to answer your questions.');

        return $this->view->setVars([
            'form_errors' => $form_errors,
            'errors' => $errors,
            'guestContactForm'  => $guestContactForm,
            'message'     => $message,
            'class'       => $class,
            'captcha' => $captcha->html()
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
            'message' => ''
        ];
        return $form_errors;
    }
}
