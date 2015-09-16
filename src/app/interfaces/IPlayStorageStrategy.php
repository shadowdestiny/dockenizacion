<?php


namespace EuroMillions\interfaces;

interface IPlayStorageStrategy
{
    public function saveAll(array $euroMillionsLine);
    public function findByKey($key);
    public function delete($key = '');

}