<?php


namespace EuroMillions\web\forms;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;

class ForgotPasswordForm extends Form
{
    public function initialize()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $email = new Email('email', array(
            'placeholder' => $translationAdapter->query('signin_email')
        ));
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The email is required.'
            )),
            new EmailValidator([
                'message' => 'Not a valid email.'
            ]),
        ));

        $this->add($email);
    }
}