<?php


namespace Core;


class Configuration
{
    private static $data;
    private static $modules;

    public static function init()
    {
        $dir = ROOT_DIR . 'config' . DIRECTORY_SEPARATOR;
        $iniFile = $dir . 'config.ini';
        $jsonFile = $dir . 'config.json';

        if(!file_exists($iniFile)){
            Response::error('config file not present');
        }

        if(file_exists($jsonFile)) {
            self::$data = json_decode(file_get_contents($jsonFile), true);
        } else {
            self::$data = parse_ini_file($iniFile, true, INI_SCANNER_TYPED);

            file_put_contents($jsonFile, json_encode(self::$data));
        }

        self::processModules();
        self::processAuthorization();
    }

    private static function processModules()
    {
        self::$modules = [];

        foreach (self::$data['modules'] as $module => $actions) {
            foreach (explode(',', $actions) as $action) {

                if (empty(self::$modules[$module])) {
                    self::$modules[$module] = [$action => false];
                } else {
                    self::$modules[$module][$action] = false;
                }
            }
        }
    }

    private static function processAuthorization()
    {
        foreach (self::$data['authorization'] as $module => $actions) {
            foreach (explode(',', $actions) as $action) {

                if (isset(self::$modules[$module][$action])) {
                    self::$modules[$module][$action] = true;
                }
            }
        }
    }

    public static function getData($key)
    {
        return self::$data[$key];
    }

    public static function validateModuleAction(string $module, string $action): bool
    {
        return empty(self::$modules[$module][$action]);
    }

    public static function shouldAuth(string $module, string $action) : bool
    {
        return isset(self::$modules[$module][$action]) ?? self::$modules[$module][$action];
    }
}
