<?php


namespace apps\web\forms;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\CreditCard;
use Phalcon\Validation\Validator\PresenceOf;

class MyAccountWalletAddFunds extends Form
{

    public function initialize()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $card_number = new Text('card-number', array(
            'placeholder' => ''
        ));
        $card_number->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("deposit_error_msg_cc")
            )),
            new CreditCard(array(
                'message' => $translationAdapter->query("deposit_error_msg_cardNotValid")
            ))
        ));
        $this->add($card_number);

        $card_holder = new Text('card-holder', array(
            'placeholder' => ''
        ));
        $card_holder->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("deposit_error_msg_name")
            ))
        ));
        $this->add($card_holder);

        $card_cvv = new Text('card-cvv', array(
            'placeholder' => ''
        ));
        $card_cvv->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("deposit_error_msg_cvv")
            ))
        ));
        $this->add($card_cvv);

        $wallet_fund = new Text('wallet-fund', array(
            'placeholder' => ''
        ));
        $wallet_fund->addValidator(array(
           new PresenceOf(array(
                'message' => 'We need funds to be able to charge.'
           ))
        ));


    }
}