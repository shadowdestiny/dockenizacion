<?php

namespace EuroMillions\shared\interfaces;

/**
 * Interface IFeatureFlagApi
 * @package EuroMillions\web\interfaces
 */
interface IFeatureFlagApi
{
    /**
     * @param $endpoint
     * @return mixed
     */
    public function sendGet($endpoint);

    /**
     * @param $endpoint
     * @param $params
     * @return mixed
     */
    public function sendPost($endpoint, $params);

    /**
     * @param $endpoint
     * @param $params
     * @return mixed
     */
    public function sendPut($endpoint, $params);

    /**
     * @param $endpoint
     * @return mixed
     */
    public function sendDelete($endpoint);
}