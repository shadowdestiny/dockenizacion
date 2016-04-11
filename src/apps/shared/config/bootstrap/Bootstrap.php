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
    private $di;
    private $strategy;

    public function __construct(IBootstrapStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->di = $this->strategy->dependencyInjector();
        $application_config = $this->di->get('config')->application;
        $error_reporting = $application_config->error_reporting;
        $display_errors = $application_config->display_errors;
        $display_startup_errors = $application_config->display_startup_errors;
        error_reporting($error_reporting);
        ini_set('display_errors', $display_errors);
        ini_set('display_startup_errors', $display_startup_errors);

    }

    public function execute()
    {
        return $this->strategy->execute($this->di);
    }
}