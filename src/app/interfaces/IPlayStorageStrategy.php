<?php


namespace EuroMillions\interfaces;


interface IPlayStorageStrategy
{
    public function saveAll(array $euroMillionsLines);
    public function findByKey($key);
    public function delete($key = '');

}