<?php
/**
 * Created by PhpStorm.
 * User: Euromillions
 * Date: 16/04/15
 * Time: 12:41
 */

namespace app\config\bootstrap;

use app\interfaces\IBootstrapStrategy;
use Phalcon;

class Bootstrap
{
    public function __construct(IBootstrapStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function execute()
    {
        $this->strategy->setErrorReportingLevel();
        $di = $this->strategy->dependencyInjector();
        $this->strategy->execute($di);
    }
}