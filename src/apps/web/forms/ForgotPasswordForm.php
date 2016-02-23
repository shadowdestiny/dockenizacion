<?php


namespace EuroMillions\web\forms;

use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;

class ForgotPasswordForm extends Form
{
    public function initialize()
    {
        $email = new Email('email', array(
            'placeholder' => 'Email'
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
    }
}