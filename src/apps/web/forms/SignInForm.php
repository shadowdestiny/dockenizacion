<?php
namespace EuroMillions\web\forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;


class SignInForm extends RedirectableFormBase
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
            new EmailValidator([
                'message' => 'Not a valid email'
            ]),
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
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
        parent::initialize();
    }
}