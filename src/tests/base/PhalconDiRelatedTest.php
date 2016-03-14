<?php
namespace EuroMillions\tests\base;

use Phalcon\Di;

trait PhalconDiRelatedTest {
    protected function getDi()
    {
        return Di::getDefault();
    }
}