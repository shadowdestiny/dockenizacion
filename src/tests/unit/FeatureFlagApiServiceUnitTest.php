<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\services\FeatureFlagApiService;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\dto\FeatureFlagDTO;
use EuroMillions\shared\vo\results\ActionResult;

class FeatureFlagApiServiceUnitTest extends UnitTestBase
{
    private $api;

    public function setUp()
    {
        $this->api = $this->prophesize('EuroMillions\web\services\external_apis\FeatureFlagApi');
        parent::setUp();
    }

    /**
     * method getItems
     * when called
     * should callApiAndReturnItems
     */
    public function test_getItems_called_callApiAndReturnItems()
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

        $expected = [
            new FeatureFlagDTO([
                'name' => 'feature-1',
                'description' => 'Description for feature 1',
                'status' => true,
                'updated_at' => '2019-02-05T15:36:21.185Z',
                'created_at' => '2019-02-04T15:31:49.956Z',
                ]
            ),
            new FeatureFlagDTO([
                'name' => 'feature-2',
                'description' => 'Description for feature 2',
                'status' => false,
                'updated_at' => '2019-02-04T10:31:49.956Z',
                'created_at' => '2019-02-03T15:31:49.956Z',
            ])
        ];

        $this->api->sendGet("/")->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->getItems();

        $this->assertEquals($expected, $actual);
    }

    /**
    * method getItems
    * when calledAndNoDataReturn
    * should callApiAndReturnEmptyArray
    */
    public function test_getItems_calledAndNoDataReturn_callApiAndReturnEmptyArray()
    {
        $responseBody = json_encode([
            'Items' => [
            ],
            'Count' => '0',
            'ScannedCount' => '0',
        ]);

        $expected = array();

        $this->api->sendGet("/")->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->getItems();

        $this->assertEquals($expected, $actual);
    }

    /**
     * method getItem
     * when called
     * should callApiAndReturnItem
     */
    public function test_getItem_called_callApiAndReturnItem()
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

        $expected = new FeatureFlagDTO([
            'name' => 'feature-1',
            'description' => 'Description for feature 1',
            'status' => true,
            'updated_at' => '2019-02-05T15:36:21.185Z',
            'created_at' => '2019-02-04T15:31:49.956Z',
            ]
        );

        $this->api->sendGet('/feature-1')->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->getItem('feature-1');

        $this->assertEquals($expected, $actual);
    }

    /**
     * method getItem
     * when called
     * should callApiAndReturnNotFound
     */
    public function test_getItem_called_callApiAndReturnNotFound()
    {
        $responseBody = json_encode([
            'Error' => 'Not Found',
            'Reference' =>  '2980eb91-4e33-429f-bfa0-f67cb9aa7390',
        ]);

        $expected = new FeatureFlagDTO([
                'name' => null,
                'description' => null,
                'status' => false,
                'updated_at' => null,
                'created_at' => null,
            ]
        );

        $this->api->sendGet('/feature-1')->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->getItem('feature-1');

        $this->assertEquals($expected, $actual);
    }

    /**
     * method updateItem
     * when called
     * should callApiAndReturnOk
     */
    public function test_updateItem_called_callApiAndReturnOk()
    {
        $responseBody = $this->getResponseMock_Ok();

        $data = array('name' => 'feature-1', 'description' => 'lorem ipsum', 'status' => true);

        $expected = new ActionResult(true, json_decode($this->getResponseMock_Ok(), true));

        $this->api->sendPut('/feature-1', ['description' => 'lorem ipsum', 'status' => true])->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->updateItem($data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method addItem
     * when called
     * should callApiAndReturnOk
     */
    public function test_addItem_called_callApiAndReturnOk()
    {
        $responseBody = $this->getResponseMock_Ok();

        $data = array('name' => 'feature-1', 'description' => 'lorem ipsum', 'status' => true);

        $expected = new ActionResult(true, json_decode($this->getResponseMock_Ok(), true));

        $this->api->sendPost('/feature-1', ['description' => 'lorem ipsum', 'status' => true])->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->addItem($data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method addItem
     * when called
     * should callApiAndReturnKeyExists
     */
    public function test_addItem_called_callApiAndReturnKeyExists()
    {
        $responseBody = json_encode([
            'Error' => 'The key already exist.',
            'Reference' =>  '2980eb91-4e33-429f-bfa0-f67cb9aa7390',
        ]);

        $data = array('name' => 'feature-exists-on-database', 'description' => 'lorem ipsum', 'status' => true);

        $expected = new ActionResult(false, json_decode($responseBody, true));

        $this->api->sendPost('/feature-exists-on-database', ['description' => 'lorem ipsum', 'status' => true])->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->addItem($data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method deleteItem
     * when called
     * should callApiAndReturnOk
     */
    public function test_deleteItem_called_callApiAndReturnOk()
    {
        $responseBody = $this->getResponseMock_Ok();

        $data = 'feature-1';

        $expected = new ActionResult(true, json_decode($this->getResponseMock_Ok(), true));

        $this->api->sendDelete('/feature-1')->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->deleteItem($data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method deleteItem
     * when called
     * should callApiAndReturnKeyNotExists
     */
    public function test_deleteItem_called_callApiAndReturnKeyNotExists()
    {
        $responseBody = json_encode([
            'Error' => 'Not Found',
            'Reference' =>  '2980eb91-4e33-429f-bfa0-f67cb9aa7390',
        ]);

        $data = 'feature-not-exists-on-database';

        $expected = new ActionResult(false, json_decode($responseBody, true));

        $this->api->sendDelete('/feature-not-exists-on-database')->willReturn($responseBody);

        $sut = $this->getSut();

        $actual = $sut->deleteItem($data);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return FeatureFlagApiService
     */
    private function getSut()
    {
        $sut = new FeatureFlagApiService($this->api->reveal());
        return $sut;
    }

    private function getResponseMock_Ok()
    {
        return json_encode([
            'status' => 'ok'
        ]);
    }

}