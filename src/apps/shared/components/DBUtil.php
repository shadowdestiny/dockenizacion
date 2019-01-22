<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 20/01/19
 * Time: 18:09
 */

namespace EuroMillions\shared\components;


class DBUtil
{

    public static function configConnection($appConfig)
    {
        if($appConfig['application']['db_master_slave_enabled'])
        {
            $conn = [
                'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
                'driver' => 'pdo_mysql',
                'charset' => 'utf8',
                'master' => [
                    'host' => $appConfig['database']['host'],
                    'user' => $appConfig['database']['username'],
                    'password' => $appConfig['database']['password'],
                    'dbname' => $appConfig['database']['dbname'],

                ],
                'slaves' => self::getSlavesFromConfigFile($appConfig)
            ];
        } else {
            $conn = [
                'host' => $appConfig['database']['host'],
                'driver' => 'pdo_mysql',
                'user' => $appConfig['database']['username'],
                'password' => $appConfig['database']['password'],
                'dbname' => $appConfig['database']['dbname'],
                'charset' => 'utf8'
            ];
        }

        return $conn;

    }

    private static function getSlavesFromConfigFile($appConfig){
        $slaves = [];

        $hosts = explode(',', $appConfig['database_slave']['host']);
        $username = explode(',', $appConfig['database_slave']['username']);
        $password = explode(',', $appConfig['database_slave']['password']);
        $dbname = explode(',', $appConfig['database_slave']['dbname']);

        foreach ($hosts as $index => $host) {
            $slave['host'] = $host;
            $slave['user'] = $username[$index];
            $slave['password'] = $password[$index];
            $slave['dbname'] = $dbname[$index];

            $slaves[] = $slave;
        }

        return $slaves;
    }


}