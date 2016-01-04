<?php
namespace tests\base;

use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Response;

abstract class ControllerTestBase extends DatabaseIntegrationTestBase
{
    /** @var Curl */
    protected $curl;

    public function setUp()
    {
        $this->curl = new Curl();
        $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $this->curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
        parent::setUp();
    }

    public function tearDown()
    {
        $this->response = null;
        parent::tearDown();
    }

    /**
     * @param string $path
     * @return Response
     */
    protected function getResponseFromUrl($path)
    {
        $url = $this->getDi()->get('url')->get($path);
        return $this->curl->get($url);
    }

    /**
     * @param Response $response
     * @return \DOMDocument
     */
    protected function getDOMfromResponse(Response $response)
    {
        $document = new \DOMDocument();
        $document->loadHTML($response->body);
        return $document;
    }

    /**
     * @param string $path
     * @return \DOMDocument
     */
    protected function getDOMfromUrl($path)
    {
        $response = $this->getResponseFromUrl($path);
        return $this->getDOMfromResponse($response);
    }

    protected function assertTagEquals($expectedTag, \DOMNode $actual)
    {
        $expected = new \DOMDocument();
        $expected->loadXML($expectedTag);
        $this->assertEqualXMLStructure($expected->firstChild, $actual);
    }
}