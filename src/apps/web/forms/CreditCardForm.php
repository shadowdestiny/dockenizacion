<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\forms\validators\CreditCardExpiryDateValidator;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\CreditCard;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class CreditCardForm extends Form
{
    public function initialize()
    {
        $card_number = new Text('card-number', array(
            'placeholder' => '',
            'autocomplete' => 'off',

        ));
        $card_number->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert a Credit Card number.'
            )),
            new StringLength(array(
               'max' => 16
            )),
            new Numericality(array(
            )),
            new CreditCard(array(
                'message' => 'The Credit Card number inserted is not valid.'
            ))
        ));

        $this->add($card_number);

        $card_holder = new Text('card-holder', array(
            'placeholder' => '',
            'autocomplete' => 'off'
        ));
        $card_holder->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert the full name of the Credit Card holder.'
            )),
            new StringLength(array(
                'min' => 5
            )),
        ));
        $this->add($card_holder);

        $card_cvv = new Text('card-cvv', array(
            'placeholder' => '',
            'autocomplete' => 'off'
        ));
        $card_cvv->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert a CVV number.'
            )),
            new Numericality(array(

            )),
            new StringLength(array(
                'min' => 3,
                'max' => 4
            ))
        ));
        $this->add($card_cvv);


        $expiry_date = new Text('expiry-date', [
            'placeholder' => 'mm/yyyy',
            'autocomplete' => 'off'
        ] );

        $expiry_date->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert an expiry date valid.'
            )),
            new CreditCardExpiryDateValidator(array(

            ))
        ));
        $this->add($expiry_date);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $csrf->clear();
        $this->add($csrf);
    }
}