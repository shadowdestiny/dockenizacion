<?php
namespace EuroMillions\forms;


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

        // Email
        $username = new Email('email', array(
            'placeholder' => 'Email'
        ));
        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'The email is required'
            )),
            new EmailValidator([
                'message' => 'Not a valid email'
            ]),
        ));
        $this->add($username);

        $fullname = new Text('fullname', [
            'placeholder' => 'Full Name'
        ]);
        $this->add($fullname);

        $content = new TextArea('content', [
            'placeholder' => 'Content'
        ]);
        $content->addValidators(array(
            new PresenceOf(array(
                'message' => 'The content is required'
            )),
        ));
        $this->add($content);

        $topic = new Select(
            'topic',
            $options['topics'],
            [
                'useEmpty' => true,
                'emptyText' => 'Please select...',

            ]
        );
        $this->add($topic);

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);

    }
}