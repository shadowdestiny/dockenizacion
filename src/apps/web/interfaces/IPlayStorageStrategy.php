<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\web\vo\UserId;


interface IPlayStorageStrategy
{
    public function save($json, UserId $userId);

    /**
     * @param PlayFormToStorage $data
     * @param UserId $userId
     * @return ActionResult
     */
    public function saveAll(PlayFormToStorage $data, UserId $userId);
    public function findByKey($key);
    public function delete($key = '');

}