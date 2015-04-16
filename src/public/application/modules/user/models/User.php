<?php

class User_Model_User extends Zend_Db_Table
{
	protected $_name = 'users';
	protected $_primary = 'user_id';
	protected $_sequence = true;

}