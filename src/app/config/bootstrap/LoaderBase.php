<?php
namespace app\config\bootstrap;

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
            'app\config'           => $this->appPath . 'config',
            'app\config\bootstrap' => $this->appPath . 'config/bootstrap',
            'app\services'         => $this->appPath . 'services',
            'app\interfaces'       => $this->appPath . 'interfaces',
            'app\entities'         => $this->appPath . 'entities',
            'tests\unit'           => $this->testsPath . "unit", //EMTD Esto podría separarse entre el WebLoader y un TestLoader
            'tests\integration'    => $this->testsPath . "integration",
            'tests\base'           => $this->testsPath . "base",
        ];
    }

    /**
     * @return Array
     */
    abstract protected function getSpecificNamespaces();
}