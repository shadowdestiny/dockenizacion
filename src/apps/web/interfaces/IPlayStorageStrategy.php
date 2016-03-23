<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\PlayFormToStorage;


interface IPlayStorageStrategy
{
    public function save($json, $userId);

    /**
     * @param PlayFormToStorage $data
     * @param $userId
     * @return ActionResult
     */
    public function saveAll(PlayFormToStorage $data, $userId);
    public function findByKey($key);
    public function delete($key = '');

}