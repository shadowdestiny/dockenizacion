<?php


namespace EuroMillions\admin\forms;



use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends Form
{
    public function initialize()
    {
        // Email
        $username = new Text('username', array(
            'placeholder' => 'Username'
        ));
        $this->add($username);
        // Password
        $password = new Password('password', array(
            'placeholder' => 'Password'
        ));
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));
        $this->add($password);
        // Remember

        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value'   => $this->security->getSessionToken(),
            'message' => 'Cross scripting protection. Reload the page.'
        )));
        $this->add($csrf);

    }
}