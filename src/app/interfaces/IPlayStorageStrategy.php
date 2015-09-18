<?php


namespace EuroMillions\interfaces;


use EuroMillions\vo\PlayFormToStorage;


interface IPlayStorageStrategy
{
    public function saveAll(PlayFormToStorage $data);
    public function findByKey($key);
    public function delete($key = '');

}