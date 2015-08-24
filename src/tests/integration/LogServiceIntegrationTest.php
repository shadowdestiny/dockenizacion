<?php
namespace tests\integration;

use EuroMillions\entities\User;
use EuroMillions\services\LoggerFactory;
use EuroMillions\services\ServiceFactory;
use EuroMillions\vo\UserId;
use tests\base\FileIntegrationTestBase;

class LogServiceIntegrationTest extends FileIntegrationTestBase
{
    /**
     * method anyMethodWithFileLog
     * when calledWithProperParams
     * should createLogFileWhenDestructionOccurs
     * @dataProvider getMethodsToExercise
     * @param string $toExercise
     * @param array $params
     */
    public function test_anyMethodWithFileLog_calledWithProperParams_createLogFileWhenDestructionOccurs($toExercise, $params)
    {
        $this->exercise($toExercise, $params);
        $this->assertFileExists($this->sandboxPath.LoggerFactory::AUTHLOG);
        $this->assertNotEmpty($this->readFile(LoggerFactory::AUTHLOG));
    }

    public function getMethodsToExercise()
    {
        return [
            ['logIn', [$this->getUserWithId()]],
            ['logOut', [$this->getUserWithId()]],
        ];
    }


    private function exercise($method, $params)
    {
        $sf = new ServiceFactory($this->getDi());
        $sut = $sf->getLogService(new LoggerFactory($this->sandboxPath));
        $sut->$method(...$params);
    }

    private function getUserWithId()
    {
        $user = new User();
        $user->setId(UserId::create());
        return $user;
    }
}