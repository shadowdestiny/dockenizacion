<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\components\DBUtil;
use EuroMillions\tests\base\UnitTestBase;

class DBUtilUnitTest extends UnitTestBase
{

   protected $appConfig = array();

   /**
   * method configConnection
   * when noSlaveEnabled
   * should returnConfig
   */
   public function test_configConnection_noSlaveEnabled_returnConfig()
   {

       $expected = [
            'host' => 'host_database',
            'driver' => 'pdo_mysql',
            'user' => 'username_database',
            'password' => 'password_database',
            'dbname' => 'dbname_database',
            'charset' => 'utf8'
       ];

       $this->setAppConfigNoSlaveEnabled();
       $result = DBUtil::configConnection($this->appConfig);
       $this->assertEquals($expected, $result);
   }

   /**
   * method configConnect
   * when SlaveEnabled
   * should returnConfig
   */
   public function test_configConnect_SlaveEnabled_returnConfig()
   {
       $this->setAppConfigSlaveEnabled();
       $result = DBUtil::configConnection($this->appConfig);

       $this->assertArrayHasKey('master', $result);
       $this->assertArrayHasKey('slaves', $result);
       $this->assertArrayHasKey('wrapperClass', $result);
   }

    /**
     * method configConnect
     * when TwoSlavesEnabled
     * should returnConfig
     */
    public function test_configConnect_TwoSlavesEnabled_returnConfig()
    {

        $expected = [
            [
                'host' => 'host_database_slave1',
                'user' => 'username_database_slave1',
                'password' => 'password_database_slave1',
                'dbname' => 'dbname_database_slave1',
                'charset' => 'utf8'
            ],
            [
                'host' => 'host_database_slave2',
                'user' => 'username_database_slave2',
                'password' => 'password_database_slave2',
                'dbname' => 'dbname_database_slave2',
                'charset' => 'utf8'
            ]
        ];

        $this->setAppConfigTwoSlavesEnabled();
        $result = DBUtil::configConnection($this->appConfig);

        $this->assertArrayHasKey('master', $result);
        $this->assertArrayHasKey('slaves', $result);
        $this->assertArrayHasKey('wrapperClass', $result);
        $this->assertCount(2,$result['slaves']);
        $this->assertEquals($expected,$result['slaves']);
    }

   private function setAppConfigNoSlaveEnabled(){
       $this->appConfig = array(
           'application' => array('db_master_slave_enabled' => 0),
           'database' => array(
               'host' => 'host_database',
               'username' => 'username_database',
               'password' => 'password_database',
               'dbname' => 'dbname_database'
           )
       );
   }

    private function setAppConfigSlaveEnabled(){
        $this->appConfig = array(
            'application' => array('db_master_slave_enabled' => 1),
            'database' => array(
                'host' => 'host_database',
                'username' => 'username_database',
                'password' => 'password_database',
                'dbname' => 'dbname_database'
            ),
            'database_slave' => array(
                'host' => 'host_database_slave',
                'username' => 'username_database_slave',
                'password' => 'password_database_slave',
                'dbname' => 'dbname_database_slave'
            )
        );
    }

    private function setAppConfigTwoSlavesEnabled(){
        $this->appConfig = array(
            'application' => array('db_master_slave_enabled' => 1),
            'database' => array(
                'host' => 'host_database',
                'username' => 'username_database',
                'password' => 'password_database',
                'dbname' => 'dbname_database'
            ),
            'database_slave' => array(
                'host' => 'host_database_slave1,host_database_slave2',
                'username' => 'username_database_slave1,username_database_slave2',
                'password' => 'password_database_slave1,password_database_slave2',
                'dbname' => 'dbname_database_slave1,dbname_database_slave2'
            )
        );
    }
}