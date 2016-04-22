<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\forms\validators\CreditCardExpiryDateValidator;
use Phalcon\Forms\Element\Hidden;
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
            'maxlength' => 16

        ));
        $card_number->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert a Credit Card number.'
            )),
            new StringLength(array(
                'min' => 16,
                'max' => 16,
                'message' => 'The Credit Card must be composed of exactly 16 characters long.'
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
                'min' => 5,
                'message' => 'Full name is too short, please add your full Name as written on the credit card.'
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
                'max' => 4,
                'message' => 'The CVV must be in between 3 and 4 characters long.'
            ))
        ));
        $this->add($card_cvv);

        $expiry_date_month = new Text('expiry-date-month', [
            'placeholder' => 'mm',
            'autocomplete' => 'off'
        ] );

        $expiry_date_month->addValidators(array(
            new PresenceOf([
                'message' => 'Insert an expiry date valid.'
            ]),
            new CreditCardExpiryDateValidator([
                'what'=>'month',
                'with' => 'expiry-date-year'
            ])
        ));
        $this->add($expiry_date_month);

        $expiry_date_year = new Text('expiry-date-year', [
            'placeholder' => 'yyyy',
            'autocomplete' => 'off'
        ] );

        $expiry_date_year->addValidators(array(
            new PresenceOf([
                'message' => 'Insert an expiry date valid.'
            ]),
            new CreditCardExpiryDateValidator([
                'what'=>'year',
                'with' => 'expiry-date-month'
            ])
        ));
        $this->add($expiry_date_year);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $csrf->clear();
        $this->add($csrf);
    }
}