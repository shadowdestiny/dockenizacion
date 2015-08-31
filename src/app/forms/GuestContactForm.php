<?php
namespace EuroMillions\forms;

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class GuestContactForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $topic = new Select('topic', [
            'placeholder' => 'Select a topic',
            'options' => $options['topics']
        ]);
        $this->add($topic);

        $name = new Text('fullname');

        //EMTD To be completed by Raul

    }
}