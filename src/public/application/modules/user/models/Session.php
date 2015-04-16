<?php

class User_Model_Session extends Zend_Db_Table
{
	protected $_name = 'sessions';
	protected $_primary = 'id';
	protected $_sequence = true;

}