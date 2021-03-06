<?php
namespace EuroMillions\tests\integration;

use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\services\LoggerFactory;
use EuroMillions\web\services\LogService;
use EuroMillions\tests\base\FileIntegrationTestBase;

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
            ['logRemember', [$this->getUserWithId()]],
            ['logRegistration', [$this->getUserWithId()]],
        ];
    }


    private function exercise($method, $params)
    {
        $sut = new LogService(new LoggerFactory($this->sandboxPath));
        $sut->$method(...$params);
    }

    private function getUserWithId()
    {
        return UserMother::anAlreadyRegisteredUser()->build();
    }
}