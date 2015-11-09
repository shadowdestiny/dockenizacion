<?php


namespace tests\unit;


use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\components\RandomPasswordGenerator;
use Phalcon\Test\UnitTestCase;

class RandomPasswordGeneratorUnitTest extends UnitTestCase
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