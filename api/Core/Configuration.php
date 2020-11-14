<?php

namespace Core;

class Configuration {

    private static $data;
    private static $modules;

    public static function init(): array {

        $dir = ROOT_DIR . 'config' . DIRECTORY_SEPARATOR;
        $iniFile = $dir . 'config.ini';
        $jsonFile = $dir . 'config.json';

        if (!file_exists($iniFile)) {

            return [false, 'config file not present'];
        }

        if (file_exists($jsonFile)) {

            list(self::$data, self::$modules) = json_decode(file_get_contents($jsonFile), true);

            if ((self::$data && count(self::$data)) && (self::$modules && count(self::$modules))) {
                return [true, ''];
            }
        }

        return processIniConfig($iniFile, $jsonFile);
    }

    protected static function processIniConfig(string $iniFile, string $jsonFile): array {

        $appConfig = parse_ini_file($iniFile, true, INI_SCANNER_TYPED);

        if (!$appConfig || !count($appConfig)) {

            return [false, 'malformed config file'];
        }

        foreach ($appConfig as $index => $config) {

            if (empty($config['actions'])) {

                self::$data[$index] = $config;
            } else {

                self::$modules[$index] = self::processIniModule(&$config);
            }
        }

        if (!file_put_contents($jsonFile, json_encode([self::$data, self::$modules]))) {

            return [false, 'Error saving config.json'];
        }

        return [true, ''];
    }

    protected static function processIniModule(array &$config): array {
        $module = [
            'actions' => explode(',', $config['actions'])
        ];

        if (isset($config['auth'])) {

            $module['auth'] = explode(',', $config['auth']);
        }

        if (isset($config['cli'])) {

            $module['cli'] = explode(',', $config['cli']);
        }

        return $module;
    }

    public static function getData($key) {

        return self::$data[$key];
    }

    public static function validateModuleAction(string $module, string $action): bool {

        return isset(self::$modules[$module]['actions'][$action]);
    }

    public static function shouldAuth(string $module, string $action): bool {

        return isset(self::$modules[$module]['auth'][$action]);
    }

    public static function isCli(string $module, string $action): bool {

        return isset(self::$modules[$module]['cli'][$action]);
    }

}
