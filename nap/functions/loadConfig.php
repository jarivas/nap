<?php
use \Exception;
use Nap\Response;

define('CONFIG_DIR', ROOT_DIR . 'config' . DIRECTORY_SEPARATOR);

$appConfig = [];//globally available

$iniFile = CONFIG_DIR . 'config.ini';
$jsonFile = CONFIG_DIR . 'config.json';

if(!file_exists($iniFile))
    Response::error('config file not present');

if(file_exists($jsonFile)) {
    $appConfig = json_decode(file_get_contents($jsonFile), true);
} else {
    $appConfig = parse_ini_file($iniFile, true, INI_SCANNER_TYPED);

    foreach($appConfig as $index => $config){
        if(isset($config['actions'])){
            $appConfig[$index]['actions'] = explode(',', $config['actions']);
            
            if(isset($config['requireAuth']))
                $appConfig[$index]['requireAuth'] = explode(',', $config['requireAuth']);
        }
    }
    file_put_contents($jsonFile, json_encode($appConfig));
}
unset($jsonFile);