<?php


namespace EuroMillions\forms;

use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;

class ForgotPasswordForm extends RedirectableFormBase
{
    public function initialize()
    {
        $email = new Email('email', array(
            'placeholder' => 'Email'
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The email is required'
            )),
            new EmailValidator([
                'message' => 'Not a valid email'
            ]),
        ));

        $this->add($email);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
        parent::initialize();

    }
}