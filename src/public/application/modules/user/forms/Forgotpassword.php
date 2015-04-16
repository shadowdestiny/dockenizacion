<?php
class User_Form_Forgotpassword extends Twitter_Form
{
    public function init()
    {
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$trans = Zend_Registry::Get('Zend_Translate');
		$this->setMethod('post')
			->setAttrib('id', 'forgotform')
			->setName('forgotform')
			->setAttrib('enctype', 'multipart/form-data');

		$formpost = new Zend_Form_Element_Hidden('formpost', array(
				'value' => '1'
		));

		$email = new Zend_Form_Element_Text('email', array(
			'required'  => true,
			'placeholder'	 => $trans->translate('email') . ' *',
			'filters'	=> array('StringTrim'),
			'size'		=> '20',
			'class'		=> 'equal',
			'required'		=> 'required',
			'maxlength'	=> '255',
			'validators' => array(
				array('StringLength', false, array(2, 255)),
				array('EmailAddress'),
			)
		));

		$submit = new Zend_Form_Element_Submit(
			'submit',
			array(
				'label'	=> $trans->translate('submit'),
				'required' => false,
				'class' => 'pull-right',
				'ignore'   => true,
			)
		);

		$this->addElements(
			array(
				$formpost,
				$email,
				$submit
		));



    }
}