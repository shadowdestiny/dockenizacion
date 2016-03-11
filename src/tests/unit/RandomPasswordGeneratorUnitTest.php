<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\components\RandomPasswordGenerator;

class RandomPasswordGeneratorUnitTest extends UnitTestBase
{

    /**
     * method randomPasswordGenerator
     * when called
     * should returnPasswordSuccess
     */
    public function test_randomPasswordGenerator_called_returnPasswordSuccess()
    {
        $randomPassword = new RandomPasswordGenerator(new NullPasswordHasher());
        $match = preg_match('/^[A-Za-z0-9_]+$/',$randomPassword->getPassword());
        $this->assertEquals(1,$match);
        $this->assertEquals(9,strlen($randomPassword->getPassword()));
        $this->assertInstanceOf('Euromillions\web\vo\Password',$randomPassword->getPassword());
    }

}