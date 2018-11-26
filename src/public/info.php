<?php
use EuroMillions\shared\config\bootstrap\Bootstrap;



$public_path = __DIR__;
$app_web_path = $public_path . '/../apps/web';
$app_path = $public_path . '/../apps/';
$global_config_path = $app_path . '../global_config/';
define('APP_PATH', $app_path);

$config_path = $app_path . 'shared/config/';
$tests_path = $public_path . '/../tests/';

require_once __DIR__.'/../vendor/autoload.php';


phpinfo(); die();