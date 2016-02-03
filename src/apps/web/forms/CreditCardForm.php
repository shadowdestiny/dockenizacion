<?php


namespace EuroMillions\web\forms;


use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\CreditCard;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class CreditCardForm extends Form
{
    public function initialize()
    {
        $card_number = new Text('card-number', array(
            'placeholder' => '',
            'autocomplete' => 'off'
        ));
        $card_number->addValidators(array(
            new PresenceOf(array(
                'message' => 'Credit Card number is required'
            )),
            new Numericality(array(
            )),
            new CreditCard(array())
        ));



        $this->add($card_number);

        $card_holder = new Text('card-holder', array(
            'placeholder' => '',
            'autocomplete' => 'off'
        ));
        $card_holder->addValidators(array(
            new PresenceOf(array(
                'message' => 'Full Name of the Credit Card holder is required'
            ))
        ));
        $this->add($card_holder);

        $card_cvv = new Text('card-cvv', array(
            'placeholder' => '',
            'autocomplete' => 'off'
        ));
        $card_cvv->addValidators(array(
            new PresenceOf(array(
                'message' => 'CVV number is required'
            )),
            new Numericality(array(

            )),
            new StringLength(array(
                'min' => 3,
                'max' => 3
            ))
        ));
        $this->add($card_cvv);
    }
}