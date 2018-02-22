<?php


namespace EuroMillions\web\forms;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\PasswordValidator;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class MyAccountChangePasswordForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $old_password = new Password('old-password', array(
            'placeholder' => $translationAdapter->query("password_old")
        ));
        $old_password->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query("password_error_msg_oldIncorrect")
        )));
        $this->add($old_password);

        $password = new Password('new-password', array(
            'placeholder' => $translationAdapter->query("password_new")
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query("password_error_msg_pass")
        )));
        $password->addValidator(new Confirmation(
            [
                'with' => 'confirm-password',
                'message' => 'Passwords inserted don\'t match.'
            ]
        ));

        $password->addValidator(new StringLength(array(
            'field' => 'new-password',
            'min' => 6,
            'messageMaximum' => $translationAdapter->query("password_error_msg_characters"),
            'messageMinimum' => $translationAdapter->query("password_error_msg_characters")
        )));


        $password->addValidator(new PasswordValidator([
            'message' => 'The password should be composed by a number, a lowercase and an uppercase character.'
        ]));
        $this->add($password);
        $password_confirm = new Password('confirm-password', array(
            'placeholder' => $translationAdapter->query("password_confirm"),
        ));

        $password_confirm->addValidator(new StringLength(array(
            'field' => 'confirm-password',
            'min' => 6,
            'messageMaximum' => $translationAdapter->query("password_error_msg_characters"),
            'messageMinimum' => $translationAdapter->query("password_error_msg_characters")
        )));

        $password_confirm->addValidator(new PresenceOf(array(
            'message' => $translationAdapter->query("password_error_msg_pass")
        )));

        $this->add($password_confirm);
    }
}
