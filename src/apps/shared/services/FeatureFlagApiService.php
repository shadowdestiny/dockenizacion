<?php

namespace EuroMillions\shared\services;

use EuroMillions\shared\interfaces\IFeatureFlagApi;
use EuroMillions\web\vo\dto\FeatureFlagDTO;
use EuroMillions\shared\vo\results\ActionResult;

class FeatureFlagApiService
{
    private $api;

    /**
     * FeatureFlagApiService constructor.
     * @param IFeatureFlagApi $api
     */
    public function __construct(IFeatureFlagApi $api)
    {
        $this->api = $api;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $body = $this->api->sendGet('/');
        $data = json_decode($body, true);

        $features = $data['Items'];

        $items = array();
        for($i=0; $i<sizeof($features); $i++){
            $items[] = new FeatureFlagDTO($features[$i]);
        }

        return $items;
    }

    /**
     * @param $name
     * @return FeatureFlagDTO
     */
    public function getItem($name)
    {
        $body = $this->api->sendGet('/'.$name);
        $data = json_decode($body, true);
        return new FeatureFlagDTO($data);
    }

    /**
     * @param array $data
     * @return ActionResult
     */
    public function updateItem(array $data)
    {
        return $this->persist($data, 'sendPut');
    }

    /**
     * @param array $data
     * @return ActionResult
     */
    public function addItem(array $data)
    {
        return $this->persist($data, 'sendPost');
    }

    /**
     * @param $name
     * @return ActionResult
     */
    public function deleteItem($name)
    {
        $response = $this->api->sendDelete('/'.$name);

        return $this->validateResponse($response);
    }

    /**
     * @param array $data
     * @param $method
     * @return ActionResult
     */
    private function persist(array $data, $method)
    {
        $feature = new FeatureFlagDTO($data);

        $params = array(
            'description' => $feature->getDescription(),
            'status' => $feature->getStatus(),
        );

        $response = $this->api->$method('/'.$feature->getName(), $params);

        return $this->validateResponse($response);
    }

    /**
     * @param $response
     * @return ActionResult
     */
    private function validateResponse($response)
    {
        $responseArray = json_decode($response, true);
        $success = true;

        if(!isset($responseArray['status']) || $responseArray['status'] != 'ok') {
            $success = false;
        }

        return new ActionResult($success, $responseArray);
    }
}