<?php
namespace EuroMillions\config\bootstrap;

use Phalcon;
use Phalcon\Di;

abstract class LoaderBase
{
    protected $appPath;
    protected $testsPath;

    public function __construct($appPath, $testsPath)
    {
        $this->appPath = $appPath;
        $this->testsPath = $testsPath;
    }

    public function register()
    {
        $loader = new Phalcon\Loader();
        $loader->registerNamespaces(array_merge(
            $this->getDefaultNamespaces(),
            $this->getSpecificNamespaces()
        ));
        $loader->register();
    }

    /**
     * @return Array
     */
    private function getDefaultNamespaces()
    {
        return [
            'EuroMillions' => $this->appPath,
//            'EuroMillions\config'                 => $this->appPath . 'config',
//            'EuroMillions\config\bootstrap'       => $this->appPath . 'config/bootstrap',
//            'EuroMillions\services'               => $this->appPath . 'services',
//            'EuroMillions\services\external_apis' => $this->appPath . 'services/external_apis',
//            'EuroMillions\interfaces'             => $this->appPath . 'interfaces',
//            'EuroMillions\entities'               => $this->appPath . 'entities',
//            'EuroMillions\forms'                  => $this->appPath . 'forms',
//            'EuroMillions\vo'                     => $this->appPath . 'vo',
//            'EuroMillions\repositories'           => $this->appPath . 'repositories',
//            'EuroMillions\exceptions'             => $this->appPath . 'exceptions',
//            'EuroMillions\components'             => $this->appPath . 'components',
//            'EuroMillions\tasks'                  => $this->appPath . 'tasks',
//            'EuroMillions\migrations_data'        => $this->appPath . 'migrations_data',
            'Phalcon'                             => $this->appPath . 'vendor/phalcon/incubator/Library/Phalcon',
            'tests\unit'                          => $this->testsPath . "unit", //EMTD Esto podría separarse entre el WebLoader y un TestLoader
            'tests\integration'                   => $this->testsPath . "integration",
            'tests\base'                          => $this->testsPath . "base",
        ];
    }

    /**
     * @return Array
     */
    abstract protected function getSpecificNamespaces();
}