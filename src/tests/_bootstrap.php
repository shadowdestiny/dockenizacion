<?php
use Phalcon\Di;

define('TESTS_PATH', __DIR__.'/');
define('APP_PATH', TESTS_PATH . '../apps/');
class EntityManagerFetcher
{
    public static function get()
    {
        return DI::getDefault()->get('entityManager');
    }
}
