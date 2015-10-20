<?php


namespace EuroMillions\interfaces;


use EuroMillions\vo\PlayFormToStorage;
use EuroMillions\vo\UserId;


interface IPlayStorageStrategy
{
    public function saveAll(PlayFormToStorage $data, UserId $userId);
    public function findByKey($key);
    public function delete($key = '');

}