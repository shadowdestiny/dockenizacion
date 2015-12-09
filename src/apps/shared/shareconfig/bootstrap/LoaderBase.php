<?php
namespace EuroMillions\shareconfig\bootstrap;

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
     * @return array
     */
    private function getDefaultNamespaces()
    {
        return [
            'EuroMillions'                 => $this->appPath,
            'Phalcon'                      => $this->appPath . 'vendor/phalcon/incubator/Library/Phalcon',
            'tests'                        => $this->testsPath,
            'EuroMillions\shareconfig'     => $this->appPath . 'shared/shareconfig',
            'EuroMillions\sharecomponents' => $this->appPath . 'shared/sharecomponents',
        ];
    }

    /**
     * @return array
     */
    abstract protected function getSpecificNamespaces();
}