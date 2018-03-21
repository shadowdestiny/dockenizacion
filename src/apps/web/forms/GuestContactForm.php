<?php

namespace EuroMillions\web\forms;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;

class GuestContactForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        // Email
        $username = new Email('email', array(
            'placeholder' => $translationAdapter->query("email")
        ));
        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'The email is required.'
            )),
            new EmailValidator([
                'message' => 'Not a valid email.'
            ]),
        ));
        $this->add($username);

        $fullname = new Text('fullname', [
            'placeholder' => $translationAdapter->query("name")
        ]);
        $fullname->addValidators([
            new PresenceOf([
                'message' => 'Full name is required'
            ])
        ]);
        $this->add($fullname);

        $content = new TextArea('message', [
            'placeholder' => $translationAdapter->query("message")
        ]);

        $content->addValidators(array(
            new PresenceOf(array(
                'message' => 'The content is required.'
            )),
        ));
        $this->add($content);

        $topic = new Select(
            'topic',
            [
                1 => $translationAdapter->query("option1"),
                2 => $translationAdapter->query("option2"),
                3 => $translationAdapter->query("option3"),
                4 => $translationAdapter->query("option4"),
                5 => $translationAdapter->query("option5")
            ],
            [
                'useEmpty' => true,
                'emptyText' => $translationAdapter->query("options"),

            ]
        );
        $this->add($topic);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);

    }
}