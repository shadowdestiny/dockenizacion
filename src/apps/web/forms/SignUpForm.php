<?php
namespace EuroMillions\web\forms;

use EuroMillions\web\components\PasswordValidator;
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
        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'The name is required.'
            )),
        ));

        $this->add($name);

        $surname = new Text('surname', [
            'placeholder' => 'Surname'
        ]);

        $surname->addValidators(array(
            new PresenceOf(array(
                'message' => 'The surname is required.'
            )),
        ));

        $this->add($surname);

        $email = new Email('email', array(
            'placeholder' => 'Email',
            'id' => 'email-sign-up'
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The email is required.'
            )),
            new EmailValidator([
                'message' => 'Not a valid email.'
            ]),
        ));

        $this->add($email);

        $password = new Password('password', array(
            'placeholder' => 'Password',
            'id' => 'password-sign-up'
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => 'Password is a required field.'
        )));

        $password->addValidator(new Confirmation(
            [
                'with' => 'confirm_password',
                'message' => 'Passwords inserted don\'t match.'
            ]
        ));
        $password->addValidator(new PasswordValidator([
            'message' => 'The password should have at least one number, a lowercase and uppercase character.'
        ]));


        $password->addValidator(new StringLength(array(
            'field' => 'password',
            'min' => 8,
            'messageMinimum' => 'Your password should be composed at least by eight letters.'
        )));


        $this->add($password);
        $password_confirm = new Password('confirm_password', array(
            'placeholder' => 'Confirm Password'
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => 'Confirm Password is a required field.'
        )));


        $this->add($password_confirm);
        // Remember

        $country = new Select(
            'country',
            $options['countries'],
            [
                'useEmpty' => true,
                'emptyText' => 'Select your country of residence.',
            ]
        );

        $country->addValidators(array(
            new PresenceOf(array(
                'message' => 'The country is required.'
            )),
        ));

        $this->add($country);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
    }
}