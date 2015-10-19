<?php


namespace EuroMillions\forms;


use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

class CreditCardForm extends Form
{
    public function initialize()
    {
        $card_number = new Text('card-number', array(
            'placeholder' => ''
        ));
        $card_number->addValidators(array(
            new PresenceOf(array(
                'message' => 'The card number is required'
            ))
        ));

        $this->add($card_number);

        $card_holder = new Text('card-holder', array(
            'placeholder' => ''
        ));
        $card_holder->addValidators(array(
            new PresenceOf(array(
                'message' => 'The card name holder is required'
            ))
        ));
        $this->add($card_holder);

        $card_cvv = new Text('card-cvv', array(
            'placeholder' => ''
        ));
        $card_cvv->addValidators(array(
            new PresenceOf(array(
                'message' => 'The cvv number is required'
            ))
        ));

        $this->add($card_cvv);
    }
}