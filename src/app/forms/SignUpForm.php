<?php
namespace EuroMillions\forms;

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


class SignUpForm extends Form
{
    public function initialize($entity, $options)
    {
        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);
        $this->add($name);

        $surname = new Text('surname', [
            'placeholder' => 'Surname'
        ]);
        $this->add($surname);

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

        $password = new Password('password', array(
            'placeholder' => 'Password'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));
        $password->addValidator(new Confirmation(
            [
                'with' => 'confirm_password',
                'message' => 'Passwords don\'t match'
            ]
        ));
        $this->add($password);
        $password_confirm = new Password('confirm_password', array(
            'placeholder' => 'Confirm Password'
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => 'The password confirmation is required'
        )));
        $this->add($password_confirm);
        // Remember

        $country = new Select('country', [
            'placeholder' => 'Select your country of residence',
            'options' => $options['countries']
        ]);
        $this->add($country);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
    }
}