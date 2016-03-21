<?php
use Phalcon\Di;
global $test_suite;
define('TESTS_PATH', __DIR__.'/');
define('APP_PATH', TESTS_PATH . '../apps/');

$command = 'php '.__DIR__.'/../vendor/doctrine/orm/bin/doctrine orm:schema-tool:create --dump-sql > '.__DIR__.'/_data/dump.sql';
exec($command);

class EntityManagerFetcher
{
    public static function get()
    {
        return DI::getDefault()->get('entityManager');
    }
}
