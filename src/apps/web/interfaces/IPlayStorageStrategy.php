<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\web\vo\UserId;


interface IPlayStorageStrategy
{
    public function saveAll(PlayFormToStorage $data, UserId $userId);
    public function findByKey($key);
    public function delete($key = '');

}