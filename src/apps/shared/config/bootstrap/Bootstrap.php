<?php
/**
 * Created by PhpStorm.
 * User: Euromillions
 * Date: 16/04/15
 * Time: 12:41
 */

namespace EuroMillions\shared\config\bootstrap;

use EuroMillions\shared\interfaces\IBootstrapStrategy;
use Phalcon;

class Bootstrap
{
    protected $di;
    public function __construct(IBootstrapStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->di = $this->strategy->dependencyInjector();
    }

    public function execute()
    {
        error_reporting($this->di->get('globalConfig')->php->error_reporting_level);
        return $this->strategy->execute($this->di);
    }
}