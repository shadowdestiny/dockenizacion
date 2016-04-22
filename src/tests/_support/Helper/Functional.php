<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\TestCase;
use EuroMillions\tests\helpers\mothers\UserMother;
use Phalcon\Di;

class Functional extends \Codeception\Module
{
    public function _before(TestCase $test)
    {
        $di = Di::getDefault();
        $environment = $di->get('environmentDetector')->get();
        $command = __DIR__.'/../../../vendor/bin/phinx seed:run --configuration="'.__DIR__.'/../../../phinx.yml" -e '.$environment;
        exec($command);
    }
    
    public function setRegisteredUser()
    {
        $user = UserMother::aRegisteredUserWithEncryptedPassword()->build();
        $data = $user->toArray();
        $this->getModule('Db')->haveInDatabase('users', $data);
        return $user;
    }
}
