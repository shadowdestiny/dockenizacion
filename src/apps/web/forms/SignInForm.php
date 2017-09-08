<?php
namespace EuroMillions\web\forms;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;


class SignInForm extends Form
{
    public function initialize()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        // Email
        $username = new Email('email', array(
            'placeholder' => $translationAdapter->query('signin_email')
        ));
        $username->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signin_msg_error_email')
            )),
            new EmailValidator([
                'message' => 'Not a valid email.'
            ]),
        ));
        $this->add($username);
        // Password
        $password = new Password('password', array(
            'placeholder' => $translationAdapter->query('signin_password')
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query('signin_msg_error_password')
        )));
        $this->add($password);
        // Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));
        $this->add($remember);
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
    }
}