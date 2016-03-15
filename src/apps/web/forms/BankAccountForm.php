<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\entities\User;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;


class BankAccountForm extends Form
{

    public function initialize(User $entity, $options = null)
    {
        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'The name is required.'
            )),
        ));

        $this->add($name);

        $surname = new Text('surname', [
            'placeholder' => 'Surname'
        ]);

        $surname->addValidators(array(
            new PresenceOf(array(
                'message' => 'The surname is required.'
            )),
        ));

        $this->add($surname);


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
                'emptyText' => 'Select your country of residence.',
            ]
        );

        $country->addValidators(array(
            new PresenceOf(array(
                'message' => 'The country is required.'
            )),
        ));

        $this->add($country);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
    }
}