<?php

namespace EuroMillions\web\forms;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class SignUpForm extends Form
{
    public function initialize($entity = null, $options = null)
    {

        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $name = new Text('name', [
            'placeholder' => $translationAdapter->query('signup_name')
        ]);

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_name')
            )),
        ));

        $this->add($name);

        $surname = new Text('surname', [
            'placeholder' => $translationAdapter->query('signup_surname')
        ]);

        $surname->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_surname')
            )),
        ));

        $this->add($surname);

        $email = new Email('email', array(
            'placeholder' => $translationAdapter->query('signup_email'),
            'id' => 'email-sign-up',
            'style' => 'width: 100%;',
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_email')
            )),
            new EmailValidator([
                'message' => 'Not a valid email.'
            ]),
        ));

        $this->add($email);

        $password = new Password('password', array(
            'placeholder' => $translationAdapter->query('signup_password'),
            'id' => 'password-sign-up',
            'style' => 'width: 100%;',
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query('signup_password')
        )));

        $password->addValidator(new Confirmation(
            [
                'with' => 'confirm_password',
                'message' => $translationAdapter->query('signup_passwordMatch')
            ]
        ));


        $password->addValidator(new StringLength(array(
            'field' => 'password',
            'min' => 6,
            'messageMinimum' => $translationAdapter->query('signup_passwordLenght')
        )));

        $password->addValidator(new RegexValidator(
            [
                "pattern" => "/^[a-zA-Z0-9_]+$/",
                "message" =>  $translationAdapter->query('signup_passwordSymbols'),
            ])
        );

        $this->add($password);
        $password_confirm = new Password('confirm_password', array(
            'placeholder' => $translationAdapter->query('signup_confirmPassword'),
            'style' => 'width: 100%;',
        ));
        $password_confirm->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query('signup_msg_error_confirmPass')
        )));


        $this->add($password_confirm);
        // Remember

        $acceptTerms = new Check( 'accept', [
            'id' => 'accept'
        ]);

        $acceptTerms->addValidator(new PresenceOf([
            'message' => $translationAdapter->query('signup_TCerror')
        ]));

        $this->add($acceptTerms);

        $country = new Select(
            'country',
            $options['countries'],
            [
                'useEmpty' => true,
                'emptyText' => $translationAdapter->query('signup_countrySelect'),
                'style' => 'width: 100%;',
            ]
        );

        $country->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_country')
            )),
        ));

        $this->add($country);

        $day = new Select(
            'day',
            ['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31'],
            [
                'useEmpty' => true,
                'emptyText' => $translationAdapter->query('signup_birthdate_day'),
            ]
        );

        $day->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_day')
            )),
        ));

        $this->add($day);

        $month = new Select(
            'month',
            ['01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June', '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December'],
            [
                'useEmpty' => true,
                'emptyText' => $translationAdapter->query('signup_birthdate_month'),
            ]
        );

        $month->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_month')
            )),
        ));

        $this->add($month);

        $year = new Select(
            'year',
            $this->getYears()
            ,[
                'useEmpty' => true,
                'emptyText' => $translationAdapter->query('signup_birthdate_year'),
            ]
        );

        $year->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_year')
            )),
        ));

        $this->add($year);

        $prefix = new Select(
            'prefix',
            [],
            [
                'useEmpty' => true,
                'emptyText' => $translationAdapter->query('signup_phone_prefix'),
            ]
        );

        $prefix->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_prefix')
            )),
        ));

        $this->add($prefix);

        $phone = new Text('phone', [
            'placeholder' => $translationAdapter->query('signup_phone'),
        ]);

        $phone->addValidators(array(
            new PresenceOf(array(
                'message' => $translationAdapter->query('signup_msg_error_phone')
            )),
            new Digit(array(
                    "message" => ":field must be numeric",
            ))
        ));

        $this->add($phone);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);
    }

    private function getYears()
    {
        $year=date('Y');
        $years=[];
        for($i=($year-100); $i<=($year-18); $i++)
        {
            $years[$i]= $i;
        }
        return $years;
    }
}
