<?php
namespace tests\base;

use Prophecy\Argument;

class ControllerUnitTestBase extends UnitTestBase
{
    protected $viewStub;
    public function setUp()
    {
        $this->viewStub = $this->prophesize('\Phalcon\Mvc\View');
        $this->viewStub->setDI(Argument::any())->willReturn(null);
    }

    public function assertSetVarCalledWithData(array $data)
    {
        foreach($data as $key => $value) {
            $this->viewStub->setVar($key, $value)->shouldBeCalled();
        }
        $this->stubDIService('view', $this->viewStub->reveal());
    }
}