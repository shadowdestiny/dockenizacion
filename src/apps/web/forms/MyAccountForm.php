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

class MyAccountForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);
        $this->add($name);
        $name->addValidators([
            new PresenceOf([
                'message' => 'The name is required.'
            ]),
        ]);

        $surname = new Text('surname', [
            'placeholder' => 'Surname'
        ]);
        $this->add($surname);
        $surname->addValidators([
            new PresenceOf([
                'message' => 'The surname is required.'
            ]),
        ]);



        $email = new Email('email', array(
            'placeholder' => 'Email'
        ));

        $this->add($email);

        $street = new Text('street', [
            'placeholder' => 'Street'
        ]);
        $this->add($street);


        $zip = new Text('zip', [
            'placeholder' => 'Zip Code'
        ]);
        $this->add($zip);
        $city = new Text('city', [
            'placeholder' => 'City'
        ]);
        $this->add($city);
        $phone_number = new Text('phone_number', [
            'placeholder' => 'Phone number'
        ]);
        $this->add($phone_number);

        $country = new Select(
            'country',
            $options['countries'],
            [
                'useEmpty' => true,
                'emptyText' => 'Select your country of residence',
            ]
        );
        $this->add($country);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));

        $this->add($csrf);
    }


    public function addPasswordElement()
    {
        $password = new Password('password', array(
            'placeholder' => 'Password'
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
        $this->add($password);
        $password_confirm = new Password('confirm_password', array(
            'placeholder' => 'Confirm Password'
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => 'The password confirmation is required.'
        )));
        $this->add($password_confirm);
    }



}