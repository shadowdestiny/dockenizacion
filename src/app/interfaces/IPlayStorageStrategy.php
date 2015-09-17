<?php


namespace EuroMillions\interfaces;

use EuroMillions\vo\UserId;

interface IPlayStorageStrategy
{
    public function saveAll(array $euroMillionsLine);
    public function findByKey($key);
    public function delete($key = '');

}