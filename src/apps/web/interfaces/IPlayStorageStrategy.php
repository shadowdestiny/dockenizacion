<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\PlayFormToStorage;


interface IPlayStorageStrategy
{
    public function save($json, $userId);

    /**
     * @param PlayFormToStorage $data
     * @param string $userId
     * @return ActionResult
     */
    public function saveAll(PlayFormToStorage $data, $userId);

    /**
     * @param string $key
     * @return ActionResult
     */
    public function findByKey($key);

    /** @return void */
    public function delete($key = '');

}