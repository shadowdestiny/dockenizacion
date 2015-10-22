<?php


namespace EuroMillions\forms;


use EuroMillions\components\PasswordValidator;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\PresenceOf;
s
class MyAccountChangePasswordForm extends RedirectableFormBase
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
        $password->addValidator(new PasswordValidator([
            'message' => 'The password should have numbers, lowercase and uppercase characters'
        ]));
        $this->add($password);
        $password_confirm = new Password('confirm-password', array(
            'placeholder' => 'Confirm Password'
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => 'The password confirmation is required'
        )));
        $this->add($password_confirm);
        parent::initialize();
    }
}