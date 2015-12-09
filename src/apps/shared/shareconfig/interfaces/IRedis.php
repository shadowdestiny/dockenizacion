<?php


namespace EuroMillions\shared\shareconfig\interfaces;


interface IRedis
{
    public function get($key);
    public function save($key,$content);
    public function delete($key);

}