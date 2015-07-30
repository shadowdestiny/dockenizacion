<?php
namespace EuroMillions\forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;


class SignInForm extends Form
{
    public function initialize()
    {
        // Email
        $username = new Email('email', array(
            'placeholder' => 'Email'
        ));
        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'The email is required'
            )),
        ));
        $this->add($username);
        // Password
        $password = new Password('password', array(
            'placeholder' => 'Password'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
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
            'message' => 'CSRF validation failed'
        )));
        $this->add($csrf);

    }
}