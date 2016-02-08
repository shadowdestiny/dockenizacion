<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\forms\elements\CreditCardExpiryDateElement;
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
                'max' => 4
            ))
        ));
        $this->add($card_cvv);


        $expiry_date = new CreditCardExpiryDateElement('expiry-date', [
            new PresenceOf([
                'message' => 'A expiry date is required.'
            ]),
            new CreditCardExpiryDateValidator([
            ])
        ]);

        $this->add($expiry_date);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
    }
}