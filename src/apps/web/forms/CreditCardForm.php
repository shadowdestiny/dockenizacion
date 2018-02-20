<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\forms\validators\CreditCardExpiryDateValidator;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
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
    public function initialize($entity, $options = null)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $card_number = new Text('card-number', array(
//            'placeholder' => $translationAdapter->query('card_number_placeholder'),
            'placeholder' => '',
            'autocomplete' => 'off',
            'maxlength' => 16

        ));
        $card_number->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('card_number_error')
            )),
            new StringLength(array(
                'min' => 16,
                'max' => 16,
                'message' => $translationAdapter->query('card_number_error')
            )),
            new Numericality(array(
            )),
            new CreditCard(array(
                'message' => 'The Credit Card number inserted is not valid.'
            ))
        ));

        $this->add($card_number);

        $card_holder = new Text('card-holder', array(
//            'placeholder' => $translationAdapter->query('card_holder_placeholder'),
            'placeholder' => '',
            'autocomplete' => 'off',
            'value' => $options['Name'] . ' ' . $options['Surname']
        ));
        $card_holder->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('card_name_error')
            )),
            new StringLength(array(
                'min' => 5,
                'message' => $translationAdapter->query('card_name_error5')
            )),
        ));
        $this->add($card_holder);

        $card_cvv = new Text('card-cvv', array(
            'placeholder' => '',
            'autocomplete' => 'off'
        ));

        $card_cvv->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('card_cvv_error')
            )),
            new Numericality(array(

            )),
            new StringLength(array(
                'min' => 3,
                'max' => 4,
                'message' => $translationAdapter->query('card_cvv_error4')
            ))
        ));
        $this->add($card_cvv);

        $expiry_date_month = new Text('expiry-date-month', [
            'placeholder' => 'mm',
            'autocomplete' => 'off'
        ] );

        $expiry_date_month->addValidators(array(
            new PresenceOf([
                'message' => $translationAdapter->query('card_date_error')
            ]),
            new CreditCardExpiryDateValidator([
                'what'=>'month',
                'with' => 'expiry-date-year'
            ])
        ));
        $this->add($expiry_date_month);

        $expiry_date_year = new Text('expiry-date-year', [
            'placeholder' => 'yy',
            'autocomplete' => 'off'
        ] );

        $expiry_date_year->addValidators(array(
            new PresenceOf([
                'message' => $translationAdapter->query('card_date_error')
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