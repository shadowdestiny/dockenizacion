<?php
namespace tests\functional\controllers;

use tests\base\ControllerTestBase;
use Phalcon\Mvc\Url;

class ControllerFunctionalTest extends ControllerTestBase
{
    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [];
    }

    /**
     * method testreact
     * when called
     * should haveProperScriptTag
     */
    public function test_testreact_called_haveProperScriptTag()
    {
        $document = $this->getDOMfromUrl('test/react');
        $script_tags = $document->getElementsByTagName('script');
        $this->assertTagEquals("<script src='/w/js/react/play.js'></script>", $script_tags->item(0));
    }
}