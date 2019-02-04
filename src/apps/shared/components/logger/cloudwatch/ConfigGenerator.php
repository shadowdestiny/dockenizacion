<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 4/09/18
 * Time: 13:40
 */

namespace EuroMillions\shared\components\logger\cloudwatch;


class ConfigGenerator
{

    public static function cloudWatchConfig($groupName, $streamName)
    {
        return [
                'aws' => [
                    'version' => 'latest',
                    'region' => 'eu-west-1',
                    'credentials' => [
                        'key' => 'AKIAI3FRNFT5QHC7GQFQ',
                        'secret' => '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'
                    ]
                ],
                'group_name' => $groupName,
                'stream_name' => $streamName
            ];

    }


}