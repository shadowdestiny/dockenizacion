<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;


class BankAccountForm extends Form
{

    public function initialize($entity, $options = null)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("withdraw_error_msg_name")
            )),
        ));

        $this->add($name);

        $surname = new Text('surname', [
            'placeholder' => 'Surname'
        ]);

        $surname->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("withdraw_error_msg_surname")
            )),
        ));

        $this->add($surname);

        $bankName = new Text('bank-name', [
        ]);
        $bankName->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("withdraw_error_msg_bank")
            )),
        ));
        $this->add($bankName);

        $bankAccount = new Text('bank-account', [
        ]);
        $bankAccount->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("withdraw_error_msg_accNum")
            )),
        ));
        $this->add($bankAccount);

        $bankSwift = new Text('bank-swift', [
        ]);
        $bankSwift->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query("withdraw_error_msg_bic")
            )),
        ));
        $this->add($bankSwift);


        $street = new Text('street', [
            'placeholder' => 'Street'
        ]);
        $street->addValidators(array(
            new PresenceOf(array(
                'message' => 'Your street address is required.'
            )),
        ));
        $this->add($street);


        $zip = new Text('zip', [
            'placeholder' => 'Zip Code'
        ]);
        $zip->addValidators(array(
            new PresenceOf(array(
                'message' => 'Your zip code is required.'
            )),
        ));
	$this->add($zip);
        
	$city = new Text('city', [
            'placeholder' => 'City'
        ]);
        $city->addValidators(array(
            new PresenceOf(array(
                'message' => 'Your city is required.'
            )),
        ));
	$this->add($city);
        
	
	$phone_number = new Text('phone_number', [
            'placeholder' => 'Phone number'
        ]);
	$phone_number->addValidators(array(
            new PresenceOf(array(
                'message' => 'Your phone number required.'
            )),
        ));
        $this->add($phone_number);

        $amount = new Text('amount', [
            'placeholder' => 'Insert an amount'
        ]);

        $amount->addValidators(array(
            new PresenceOf(array(
                'message' => 'Insert an amount > 25.'
            )),
            new Numericality(array(
            )),
        ));
        $amount->clear();
        $this->add($amount);


        $country = new Select(
            'country',
            $options['countries'],
            [
                'useEmpty' => true,
                'emptyText' => 'Select your country of residence.',
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
}
