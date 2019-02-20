<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\services\external_apis\FeatureFlagApi;
use EuroMillions\tests\base\UnitTestBase;
use Phalcon\Http\Client\Response;
use Prophecy\Argument;

class FeatureApiUnitTest extends UnitTestBase
{
    private $curl_double;

    const ENDPOINT = 'https://apigateway.enpoint.com/beta'; //from test_config.ini
    const API_KEY = 'apikey'; //from test_config.ini

    public function setUp()
    {
        $this->curl_double = $this->prophesize('\Phalcon\Http\Client\Provider\Curl');
        parent::setUp();
    }

    /**
     * method sendGet
     * when called
     * should callApiAndReturnItems
     */
    public function test_sendGet_called_callApiAndReturnItems()
    {
        $responseBody = json_encode([
            'Items' => [
                [
                    'name' => 'feature-1',
                    'description' => 'Description for feature 1',
                    'status' => true,
                    'updated_at' => '2019-02-05T15:36:21.185Z',
                    'created_at' => '2019-02-04T15:31:49.956Z',
                ],
                [
                    'name' => 'feature-2',
                    'description' => 'Description for feature 2',
                    'status' => false,
                    'updated_at' => '2019-02-04T10:31:49.956Z',
                    'created_at' => '2019-02-03T15:31:49.956Z',
                ],
            ],
            'Count' => '2',
            'ScannedCount' => '2',
        ]);

        $expected = $responseBody;

        $this->curl_double->get(self::ENDPOINT."/",
            [],
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendGet("/");

        $this->assertEquals($expected, $actual);
    }

    /**
    * method sendGet
    * when calledAndNoDataReturn
    * should callApiAndReturnEmptyArray
    */
    public function test_sendGet_calledAndNoDataReturn_callApiAndReturnEmptyArray()
    {
        $responseBody = json_encode([
            'Items' => [
            ],
            'Count' => '0',
            'ScannedCount' => '0',
        ]);

        $expected = $responseBody;

        $this->curl_double->get(self::ENDPOINT."/",
            [],
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendGet("/");

        $this->assertEquals($expected, $actual);
    }

    /**
     * method sendGet
     * when called
     * should callApiAndReturnItem
     */
    public function test_sendGet_called_callApiAndReturnItem()
    {
        $responseBody = json_encode([
            'Item' => [
                'name' => 'feature-1',
                'description' => 'Description for feature 1',
                'status' => true,
                'updated_at' => '2019-02-05T15:36:21.185Z',
                'created_at' => '2019-02-04T15:31:49.956Z',
            ]
        ]);

        $expected = $responseBody;

        $this->curl_double->get(self::ENDPOINT."/feature-1",
            [],
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendGet('/feature-1');

        $this->assertEquals($expected, $actual);
    }

    /**
     * method sendPut
     * when called
     * should callApiAndReturnOk
     */
    public function test_sendPut_called_callApiAndReturnOk()
    {
        $responseBody = $this->getResponseMock_Ok();

        $endpoint = '/feature-1';
        $data = array('name' => 'feature-1', 'description' => 'lorem ipsum', 'status' => true);

        $expected = $this->getResponseMock_Ok();

        $this->curl_double->put(self::ENDPOINT."/feature-1",
            Argument::any(),
            true,
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendPut($endpoint, $data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method sendPost
     * when called
     * should callApiAndReturnOk
     */
    public function test_sendPost_called_callApiAndReturnOk()
    {
        $responseBody = $this->getResponseMock_Ok();

        $endpoint = '/feature-1';
        $data = array('name' => 'feature-1', 'description' => 'lorem ipsum', 'status' => true);

        $expected = $this->getResponseMock_Ok();

        $this->curl_double->post(self::ENDPOINT."/feature-1",
            Argument::any(),
            true,
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendPost($endpoint, $data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method sendPost
     * when called
     * should callApiAndReturnKeyExists
     */
    public function test_sendPost_called_callApiAndReturnKeyExists()
    {
        $responseBody = json_encode([
            'Error' => 'The key already exist.',
            'Reference' =>  '2980eb91-4e33-429f-bfa0-f67cb9aa7390',
        ]);

        $endpoint = '/feature-exists-on-database';
        $data = array('name' => 'feature-exists-on-database', 'description' => 'lorem ipsum', 'status' => true);

        $expected = $responseBody;

        $this->curl_double->post(self::ENDPOINT."/feature-exists-on-database",
            Argument::any(),
            true,
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendPost($endpoint, $data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method sendDelete
     * when called
     * should callApiAndReturnOk
     */
    public function test_sendDelete_called_callApiAndReturnOk()
    {
        $responseBody = $this->getResponseMock_Ok();

        $data = '/feature-1';

        $expected = $this->getResponseMock_Ok();

        $this->curl_double->delete(self::ENDPOINT."/feature-1",
            Argument::any(),
            true,
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendDelete($data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method sendDelete
     * when called
     * should callApiAndReturnKeyNotExists
     */
    public function test_sendDelete_called_callApiAndReturnKeyNotExists()
    {
        $responseBody = json_encode([
            'Error' => 'Not Found',
            'Reference' =>  '2980eb91-4e33-429f-bfa0-f67cb9aa7390',
        ]);

        $data = '/feature-not-exists-on-database';

        $expected = $responseBody;

        $this->curl_double->delete(self::ENDPOINT."/feature-not-exists-on-database",
            Argument::any(),
            true,
            ["x-api-key: ".self::API_KEY."", "Content-Type: application/json; charset=utf-8"]
        )->willReturn($this->getResponseObject($responseBody));

        $sut = $this->getSut();

        $actual = $sut->sendDelete($data);

        $this->assertEquals($expected, $actual);
    }

    private function getResponseObject($result)
    {
        $response = new Response();
        $response->body = $result;
        return $response;
    }

    /**
     * @return FeatureFlagApi
     * @throws \Phalcon\Http\Client\Exception
     * @throws \Phalcon\Http\Client\Provider\Exception
     */
    private function getSut()
    {
        $sut = new FeatureFlagApi($this->curl_double->reveal());
        return $sut;
    }

    private function getResponseMock_Ok()
    {
        return json_encode([
            'status' => 'ok'
        ]);
    }

}