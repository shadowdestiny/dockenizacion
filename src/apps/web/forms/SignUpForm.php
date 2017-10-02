<?php

namespace EuroMillions\web\forms;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\StringLength;

class SignUpForm extends Form
{
    public function initialize($entity = null, $options = null)
    {

        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $name = new Text('name', [
            'placeholder' => $translationAdapter->query('signup_name')
        ]);

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_name')
            )),
        ));

        $this->add($name);

        $surname = new Text('surname', [
            'placeholder' => $translationAdapter->query('signup_surname')
        ]);

        $surname->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_surname')
            )),
        ));

        $this->add($surname);

        $email = new Email('email', array(
            'placeholder' => $translationAdapter->query('signup_email'),
            'id' => 'email-sign-up'
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_email')
            )),
            new EmailValidator([
                'message' => 'Not a valid email.'
            ]),
        ));

        $this->add($email);

        $password = new Password('password', array(
            'placeholder' => $translationAdapter->query('signup_password'),
            'id' => 'password-sign-up'
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query('signup_password')
        )));

        $password->addValidator(new Confirmation(
            [
                'with' => 'confirm_password',
                'message' => $translationAdapter->query('signup_passwordMatch')
            ]
        ));


        $password->addValidator(new StringLength(array(
            'field' => 'password',
            'min' => 6,
            'messageMinimum' => $translationAdapter->query('signup_passwordLenght')
        )));


        $this->add($password);
        $password_confirm = new Password('confirm_password', array(
            'placeholder' => $translationAdapter->query('signup_confirmPassword')
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query('signup_msg_error_confirmPass')
        )));


        $this->add($password_confirm);
        // Remember

        $country = new Select(
            'country',
            $options['countries'],
            [
                'useEmpty' => true,
                'emptyText' => $translationAdapter->query('signup_countrySelect'),
            ]
        );

        $country->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_country')
            )),
        ));

        $this->add($country);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
    }
}
