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
                'slaves' => [
                    [
                        'host' => $appConfig['database_slave']['host'],
                        'user' => $appConfig['database_slave']['username'],
                        'password' => $appConfig['database_slave']['password'],
                        'dbname' => $appConfig['database_slave']['dbname'],
                    ]
                ]
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


}