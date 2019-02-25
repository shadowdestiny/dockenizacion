<?php


namespace tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\dto\FeatureFlagDTO;

class FeatureFlagDTOUnitTest extends UnitTestBase
{

    /**
    * method __construct
    * when called
    * should createAProperDTOWithProperData
    */
    public function test___construct_called_createAProperDTOWithProperData()
    {
        $data = [
            'name' => 'A cool Name',
            'description' => 'A description',
            'status' => false,
            'created_at' => '2019-02-04T15:31:49.956Z',
            'updated_at' => '2019-02-04T15:31:49.956Z',
        ];

        $sut = new FeatureFlagDTO($data);

        $this->assertEquals($data['name'], $sut->getName());
        $this->assertEquals($data['description'], $sut->getDescription());
        $this->assertEquals($data['status'], $sut->getStatus());
        $this->assertEquals(1549294309, $sut->getCreatedAt());
        $this->assertEquals(1549294309, $sut->getUpdatedAt());
    }

    /**
    * method __construct
    * when calledWithNullData
    * should createAProperDTOWithNullData
    */
    public function test___construct_calledWithNullData_createAProperDTOWithNullData()
    {
        $sut = new FeatureFlagDTO();

        $this->assertEquals(null, $sut->getName());
        $this->assertEquals(null, $sut->getDescription());
        $this->assertEquals(null, $sut->getStatus());
        $this->assertEquals(null, $sut->getCreatedAt());
        $this->assertEquals(null, $sut->getUpdatedAt());
    }

    /**
    * method setStatus
    * when calledWithString
    * should setsBoolean
    */
    public function test_setStatus_calledWithString_setsBoolean()
    {
        $sut = new FeatureFlagDTO();
        $sut->setStatus("1");

        $this->assertEquals(true, $sut->getStatus());
        $this->assertTrue(is_bool($sut->getStatus()));

        $this->assertEquals(1, $sut->getStatus(false));
        $this->assertTrue(is_int($sut->getStatus(false)));
    }
}