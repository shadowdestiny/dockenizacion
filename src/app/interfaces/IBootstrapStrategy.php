<?php
/**
 * Created by PhpStorm.
 * User: Euromillions
 * Date: 16/04/15
 * Time: 12:56
 */

namespace app\interfaces;

use Phalcon\Di;

interface IBootstrapStrategy
{
    public function setErrorReportingLevel();

    /**
     * @return \Phalcon\Di
     */
    public function dependencyInjector();

    public function execute(Di $di);
}