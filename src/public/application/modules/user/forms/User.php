<?php

class User_Form_User extends Twitter_Form
{
	public function init()
	{
		$trans = Zend_Registry::Get('Zend_Translate');
		$this->setMethod('post')
			->setAttrib('id', 'user')
			->setName('user')
			->setAttrib('enctype', 'multipart/form-data');


			$user_id = new Zend_Form_Element_Hidden('user_id');
			$user_id->setValue(0);

			$username = new Zend_Form_Element_Text('username', array(
					'required'  => true,
					'label'	 => $trans->translate('username'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '20',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));

			$password = new Zend_Form_Element_Text('password', array(
					'required'  => true,
					'label'	 => $trans->translate('password'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '20',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));

			$firstname = new Zend_Form_Element_Text('first_name', array(
					'required'  => true,
					'label'	 => $trans->translate('firstname'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '20',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));

			$lastname = new Zend_Form_Element_Text('last_name', array(
					'required'  => true,
					'label'	 => $trans->translate('lastname'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '20',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));

			$gender = new Zend_Form_Element_Select('gender', array(
				'required'  => true,
				//'class'=>'select',
				'label'	 => $trans->translate('gender'),
				'multiOptions' => Array("m"=>"Mann","f"=>"Frau","n"=>"Keine Angabe"),
			));

			$email = new Zend_Form_Element_Text('email', array(
					'required'  => false,
					'label'	 => $trans->translate('email'),
					'filters'	=> array('StringTrim'),
					'size'		=> '128',
					'maxlength'	=> '128',
					'validators' => array(
						array('StringLength', false, array(2, 128)),
					)
			));

		$role = new Zend_Form_Element_Select('role', array(
				'required'  => true,
				//'class'=>'select',
				'label'	 => $trans->translate('role'),
				'multiOptions' => Array("user"=>"User","admin"=>"Admin","superadmin"=>"Superadmin"),
			));

		$image = new Zend_Form_Element_File('image',Array(
              'label'=>$trans->translate('image'),
			  'required'=>False,
			  'class'   => 'fileupload',
			  //'Destination'=>APPLICATION_PATH.'/../media/1/',
			  'ValueDisabled'=>'true'
			  ));

		$submit = new Zend_Form_Element_Submit(
			'save',
			array(
				'label'	=> $trans->translate('save'),
				'class'	   => $trans->translate('save'),
				'required' => false,
				'ignore'   => true,
			)
		);


		$this->addElements(
			array(
				$user_id,
				$username,
				$firstname,
				$lastname,
				$password,
				$gender,
				$email,
				$role,
				$image,
				$submit
				));


/*
				$software_id,
				$title,
				$category_id,
				$short_desc,
				$long_desc,
				$publisher,
				$filesize,
				$version,
				$url,
				$taglist,
				$file,
				$screenshot1,
				$screenshot2,
				$screenshot3,
				$screenshot4,
				$submit
				));
*/
        //$this->removeDecorator('HtmlTag');

        foreach ($this->getElements() as $element) {
			//$element->removeDecorator('Label');
            //$element->removeDecorator('HtmlTag');
            //$element->removeDecorator('DtDdWrapper');
        }


	}

	public function a()
	{

			$checktitle = new Zend_Validate_Db_NoRecordExists(array('table' => "programs",'field' => 'title'));
			$checktitle->setMessage("Dieses Programm gibt es bereits!");

			$software_id = new Zend_Form_Element_Hidden('software_id');
			$software_id->setValue(0);


			$title= new Zend_Form_Element_Text('title', array(
					'required'  => true,
					'label'	 => 'Programmname:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));


			$short_desc= new Zend_Form_Element_Textarea('short_desc', array(
					'required'  => true,
					'class'		=> 'tinymce',
					'label'=>"Kurzbeschreibung",
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '65000',
					'validators' => array(
						array('StringLength', false, array(3, 65000)),
					)
			));

			$long_desc= new Zend_Form_Element_Textarea('long_desc', array(
					'required'  => true,
					'class'		=> 'tinymce',
					'label'=>"Kurzbeschreibung",
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '65000',
					'validators' => array(
						array('StringLength', false, array(3, 65000)),
					)
			));

/*
		$obj_c = new Dl_Model_Category();
		$select=$obj_c->select();
		$select->order("name");
		$catlist=$obj_c->fetchAll($select);

		foreach($catlist as $cat)
		{
			$arrCatList[$cat->category_id]=$cat->name;
		}

		$category_id = new Zend_Form_Element_Select('category_id', array(
				'required'  => true,
				//'class'=>'select',
				'label'	 => 'Kategorie:',
				'multiOptions' => $arrCatList,
			));
*/
			$active = new Zend_Form_Element_Select('active', array(
				'required'  => true,
				//'class'=>'select',
				'label'	 => 'Aktiv:',
				'multiOptions' => Array(1=>"Ja",0=>"Nein"),
			));

			$publisher = new Zend_Form_Element_Text('publisher', array(
					'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					'label'	 => 'Hersteller:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$filesize = new Zend_Form_Element_Text('filesize', array(
					'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					'label'	 => 'Dateigröße',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

		$version = new Zend_Form_Element_Text('version', array(
					'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					'label'	 => 'Version:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

		$url = new Zend_Form_Element_Text('url', array(
					'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					'label'	 => 'URL:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));
/*
			$phone_ch_guest = new Zend_Form_Element_Text('phone_ch_guest', array(
					//'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$price_ch_guest = new Zend_Form_Element_Text('price_ch_guest', array(
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

						$phone_de_free = new Zend_Form_Element_Text('phone_de_free', array(
					//'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$price_de_free = new Zend_Form_Element_Text('price_de_free', array(
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$phone_at_free = new Zend_Form_Element_Text('phone_at_free', array(
					//'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$price_at_free = new Zend_Form_Element_Text('price_at_free', array(
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$phone_ch_free = new Zend_Form_Element_Text('phone_ch_free', array(
					//'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$price_ch_free = new Zend_Form_Element_Text('price_ch_free', array(
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));


						$phone_de_premium = new Zend_Form_Element_Text('phone_de_premium', array(
					//'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$price_de_premium = new Zend_Form_Element_Text('price_de_premium', array(
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$phone_at_premium = new Zend_Form_Element_Text('phone_at_premium', array(
					//'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$price_at_premium = new Zend_Form_Element_Text('price_at_premium', array(
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$phone_ch_premium = new Zend_Form_Element_Text('phone_ch_premium', array(
					//'required'  => true,
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$price_ch_premium = new Zend_Form_Element_Text('price_ch_premium', array(
					//'class'		=> 'fg-button ui-state-default',
					//'label'	 => 'Titel:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));
      $fsk16_1 = new Zend_Form_Element_File('fsk16_1',Array(
              'required'=>False,
			  'Destination'=>APPLICATION_PATH.'/../tmp/phonesex/',
			  'ValueDisabled'=>'true'
			  ));
      //$fsk16_1->addValidator('Count', false, 1);
      //$fsk16_1->addValidator('Size', false, 5555555);
      //$fsk16_1->addValidator('Extension', false, 'jpg,png,gif');

	$fsk16_2 = new Zend_Form_Element_File('fsk16_2',Array(
              'required'=>False,
			  'Destination'=>APPLICATION_PATH.'/../tmp/phonesex/',
			  'ValueDisabled'=>'true'
			  ));

	$fsk16_3 = new Zend_Form_Element_File('fsk16_3',Array(
				'required'=>False,
				'Destination'=>APPLICATION_PATH.'/../tmp/phonesex/',
				'ValueDisabled'=>'true'
			  ));

	$fsk18_1 = new Zend_Form_Element_File('fsk18_1',Array(
              'required'=>False,
			  'Destination'=>APPLICATION_PATH.'/../tmp/phonesex/',
			  'ValueDisabled'=>'true'
			  ));

	$fsk18_2 = new Zend_Form_Element_File('fsk18_2',Array(
              'required'=>False,
			  'Destination'=>APPLICATION_PATH.'/../tmp/phonesex/',
			  'ValueDisabled'=>'true'
			  ));

	$fsk18_3 = new Zend_Form_Element_File('fsk18_3',Array(
              'required'=>False,
			  'Destination'=>APPLICATION_PATH.'/../tmp/phonesex/',
			  'ValueDisabled'=>'true'
			  ));
*/

		/*
		$arrTagList=Array();
		$taglist = new Zend_Form_Element_MultiSelect('taglist', array(
			'required'  => true,
			//'class'=>'select',
			'label'	 => 'Tags:',
			'multiOptions' => $arrTagList,
		));
		*/

		$file = new Zend_Form_Element_File('file',Array(
		'required'=>true,
		'Destination'=>APPLICATION_PATH.'/../media/stream/',
		'ValueDisabled'=>'true'
		));
		/*
		$screenshot1 = new Zend_Form_Element_File('screenshot1',Array(
		'required'=>false,
		'Destination'=>APPLICATION_PATH.'/../tmp/',
		'ValueDisabled'=>'true'
		));
*/
/*
		$screenshot2 = new Zend_Form_Element_File('screenshot2',Array(
		'required'=>false,
		'Destination'=>APPLICATION_PATH.'/../tmp/',
		'ValueDisabled'=>'true'
		));
*/
/*
		$screenshot3 = new Zend_Form_Element_File('screenshot3',Array(
		'required'=>false,
		'Destination'=>APPLICATION_PATH.'/../tmp/',
		'ValueDisabled'=>'true'
		));
*/
/*
		$screenshot4 = new Zend_Form_Element_File('screenshot4',Array(
		'required'=>false,
		'Destination'=>APPLICATION_PATH.'/../tmp/',
		'ValueDisabled'=>'true'
		));
*/
		$submit = new Zend_Form_Element_Submit(
			'save',
			array(
				'label'	=> 'Speichern',
				'class'	   => 'save ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon',
				'required' => false,
				'ignore'   => true,
			)
		);

	}
}