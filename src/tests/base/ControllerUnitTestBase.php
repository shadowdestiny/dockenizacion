<?php
namespace tests\base;

class ControllerUnitTestBase extends UnitTestBase
{
    public function assertSetVarCalledWithData(array $data)
    {
        $view_stub = $this->prophesize('\Phalcon\Mvc\View');
        foreach($data as $key => $value) {
            $view_stub->setVar($key, $value)->shouldBeCalled();
        }
        $this->stubDIService('view', $view_stub->reveal());
    }
}