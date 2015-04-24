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
        $di = $this->strategy->dependencyInjector();
        error_reporting($di->get('globalConfig')->php->error_reporting_level);
        $this->strategy->execute($di);
    }
}