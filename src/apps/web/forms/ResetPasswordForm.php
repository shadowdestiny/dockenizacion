<?php


namespace EuroMillions\web\forms;



use EuroMillions\web\components\PasswordValidator;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class ResetPasswordForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $password = new Password('new-password', array(
            'placeholder' => 'New password'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'Password is a required field.'
        )));
        $password->addValidator(new Confirmation(
            [
                'with' => 'confirm-password',
                'message' => 'Passwords inserted don\'t match.'
            ]
        ));
        $password->addValidator(new PasswordValidator([
            'message' => 'The password should be composed by a number, a lowercase and an uppercase character.'
        ]));


        $password->addValidator(new StringLength(array(
            'field' => 'new-password',
            'min' => 6,
            'messageMaximum' => 'Your password should be composed by at least by six characters.',
            'messageMinimum' => 'Your password should be composed by at least by six characters.'
        )));


        $this->add($password);
        $password_confirm = new Password('confirm-password', array(
            'placeholder' => 'Confirm Password'
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => 'Confirm Password is a required field.'
        )));

        $this->add($password_confirm);

    }

}
