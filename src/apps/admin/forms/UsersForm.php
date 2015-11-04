<?php


namespace EuroMillions\admin\forms;


use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;

class UsersForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);
        $this->add($name);
        $name->addValidators([
            new PresenceOf([
                'message' => 'The name is required'
            ]),
        ]);

        $surname = new Text('surname', [
            'placeholder' => 'Surname'
        ]);
        $this->add($surname);
        $surname->addValidators([
            new PresenceOf([
                'message' => 'The surname is required'
            ]),
        ]);

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

        $street = new Text('street', [
            'placeholder' => 'Street'
        ]);
        $this->add($street);
        $street->addValidators([
            new PresenceOf([
                'message' => 'The street is required'
            ]),
        ]);


        $zip = new Text('zip', [
            'placeholder' => 'Zip Code'
        ]);
        $this->add($zip);
        $zip->addValidators([
            new PresenceOf([
                'message' => 'The zip is required'
            ]),
        ]);

        $city = new Text('city', [
            'placeholder' => 'City'
        ]);
        $this->add($city);
        $city->addValidators([
            new PresenceOf([
                'message' => 'The city is required'
            ]),
        ]);


        $phone_number = new Text('phone_number', [
            'placeholder' => 'Phone number'
        ]);
        $this->add($phone_number);
        $phone_number->addValidators([
            new PresenceOf([
                'message' => 'A phone number is required'
            ]),
        ]);

        /*$password = new Password('password', array(
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
        $password->addValidator(new PasswordValidator([
            'message' => 'The password should have numbers, lowercase and uppercase characters'
        ]));
        $this->add($password);
        $password_confirm = new Password('confirm_password', array(
            'placeholder' => 'Confirm Password'
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => 'The password confirmation is required'
        )));
        $this->add($password_confirm);*/
        // Remember

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
        parent::initialize();
    }



}