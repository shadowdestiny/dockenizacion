<?php


namespace apps\web\forms;


use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\CreditCard;
use Phalcon\Validation\Validator\PresenceOf;

class MyAccountWalletAddFunds extends Form
{

    public function initialize()
    {
        $card_number = new Text('card-number', array(
            'placeholder' => ''
        ));
        $card_number->addValidators(array(
            new PresenceOf(array(
                'message' => 'Credit Card number is required'
            )),
            new CreditCard(array(
                'message' => 'Credit Card number is invalid'
            ))
        ));
        $this->add($card_number);

        $card_holder = new Text('card-holder', array(
            'placeholder' => ''
        ));
        $card_holder->addValidators(array(
            new PresenceOf(array(
                'message' => 'Full Name of the Credit Card holder is required'
            ))
        ));
        $this->add($card_holder);

        $card_cvv = new Text('card-cvv', array(
            'placeholder' => ''
        ));
        $card_cvv->addValidators(array(
            new PresenceOf(array(
                'message' => 'CVV number is required'
            ))
        ));
        $this->add($card_cvv);

        $wallet_fund = new Text('wallet-fund', array(
            'placeholder' => ''
        ));
        $wallet_fund->addValidator(array(
           new PresenceOf(array(
                'message' => 'We need a funds to charge'
           ))
        ));


    }
}