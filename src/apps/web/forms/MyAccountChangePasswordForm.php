<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\components\PasswordValidator;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class MyAccountChangePasswordForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $old_password = new Password('old-password', array(
            'placeholder' => 'Old password'
        ));
        $old_password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));
        $this->add($old_password);

        $password = new Password('new-password', array(
            'placeholder' => 'Password'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));
        $password->addValidator(new Confirmation(
            [
                'with' => 'confirm-password',
                'message' => 'Passwords don\'t match'
            ]
        ));

        $password->addValidator(new StringLength(array(
            'field' => 'new-password',
            'max' => 8,
            'min' => 8,
            'messageMaximum' => 'Your password should be at composed by 8 characters.',
            'messageMinimum' => 'Your password should be at composed by 8 characters.'
        )));


        $password->addValidator(new PasswordValidator([
            'message' => 'The password should have a number, a lowercase and an uppercase character.'
        ]));
        $this->add($password);
        $password_confirm = new Password('confirm-password', array(
            'placeholder' => 'Confirm Password'
        ));

        $password_confirm->addValidator(new StringLength(array(
            'field' => 'confirm-password',
            'max' => 8,
            'min' => 8,
            'messageMaximum' => 'Your password should be at composed by 8 characters.',
            'messageMinimum' => 'Your password should be at composed by 8 characters.'
        )));

        $password_confirm->addValidator(new PresenceOf(array(
            'message' => 'The password confirmation is required'
        )));

        $this->add($password_confirm);
    }
}