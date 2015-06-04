<?php
namespace EuroMillions\services;

use Phalcon\Di;

class PhalconService
{
    protected $di;

    public function __construct()
    {
        $this->di = Di::getDefault();
    }
}