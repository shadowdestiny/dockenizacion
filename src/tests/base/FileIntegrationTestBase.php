<?php
namespace EuroMillions\tests\base;

use Phalcon\DI;

abstract class FileIntegrationTestBase extends \PHPUnit_Framework_TestCase
{
    use PhalconDiRelatedTest;
    use TestHelperTrait;

    protected $sandboxPath;

    protected function setUp()
    {
        $this->sandboxPath = $this->getDi()->get('testsPath').'integration/sandbox/';
        parent::setUp();
    }
    protected function tearDown()
    {
        $files = glob($this->sandboxPath.'*');
        foreach ($files as $file) {
            if (strpos($file, 'README.md') === false) {
                unlink($file);
            }
        }
        parent::tearDown();
    }

    protected function readFile($filename)
    {
        return file_get_contents($this->sandboxPath.$filename);
    }
}