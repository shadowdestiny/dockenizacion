<?php

namespace EuroMillions\shared\services;

use EuroMillions\shared\interfaces\IFeatureFlagApi;
use EuroMillions\web\vo\dto\FeatureFlagDTO;

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
     * @return bool
     */
    public function updateItem(array $data)
    {
        $feature = new FeatureFlagDTO();
        $feature->setName($data['name']);
        $feature->setDescription($data['description']);
        $feature->setStatus($data['status']);

        $params = array(
            'description' => $feature->getDescription(),
            'status' => $feature->getStatus(),
        );

        $response = $this->api->sendPut('/'.$feature->getName(), $params);

        return $this->validateResponse($response);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function addItem(array $data)
    {
        $feature = new FeatureFlagDTO();
        $feature->setName($data['name']);
        $feature->setDescription($data['description']);
        $feature->setStatus($data['status']);

        $params = array(
            'description' => $feature->getDescription(),
            'status' => $feature->getStatus(),
        );

        $response = $this->api->sendPost('/'.$feature->getName(), $params);

        return $this->validateResponse($response);
    }

    /**
     * @param $name
     * @return bool
     */
    public function deleteItem($name)
    {
        $response = $this->api->sendDelete('/'.$name);

        return $this->validateResponse($response);
    }

    /**
     * @param $response
     * @return bool
     */
    private function validateResponse($response)
    {
        $responseArray = json_decode($response, true);
        if(!isset($responseArray['status']) || $responseArray['status'] != 'ok'){
            return false;
        }

        return $response;
    }
}