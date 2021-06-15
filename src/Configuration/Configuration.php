<?php

namespace Nap\Configuration;

use Nap\Response;

class Configuration
{
    protected static array $data;
    protected static array $modules;

    /**
     * @param string $iniFile
     * @return null|array
     */
    public static function initByIni(string $iniFile): ?array
    {
        $jsonFile = str_replace('.ini', '.json', $iniFile);

        if (!file_exists($iniFile)) {
            return ['ini config file not present', Response::FATAL_INTERNAL_ERROR];
        }

        return self::processIniConfig($iniFile, $jsonFile);
    }

    /**
     * @param string $jsonFile
     * @return null|array
     */
    public static function initByJson(string $jsonFile): ?array
    {
        if (file_exists($jsonFile)) {
            return ['json config file not present', Response::FATAL_INTERNAL_ERROR];
        }

        list(self::$data, self::$modules) = json_decode(file_get_contents($jsonFile), true);

        if ((self::$data && count(self::$data)) && (self::$modules && count(self::$modules))) {
            return null;
        }

        return ['json config file has not the right format', Response::FATAL_INTERNAL_ERROR];
    }

    /**
     *
     * @param string $key
     * @return null|array
     */
    public static function getData(string $key): ?array
    {
        return empty(self::$data[$key]) ? null : self::$data[$key];
    }

    /**
     *
     * @param string $module
     * @return null|array
     */
    public static function getModuleData(string $module): ?array
    {
        return empty(self::$modules[$module]) ? null : self::$modules[$module];
    }

    /**
     *
     * @param string $module
     * @param string $action
     * @return bool
     */
    public static function validateModuleAction(string $module, string $action): bool
    {
        return !empty(self::$modules[$module]) && in_array($action, self::$modules[$module]['actions']);
    }

    /**
     *
     * @param string $module
     * @param string $action
     * @return bool
     */
    public static function shouldAuth(string $module, string $action): bool
    {
        return !empty(self::$modules[$module]) && in_array($action, self::$modules[$module]['auth']);
    }

    /**
     * @param string $iniFile
     * @param string $jsonFile
     * @return null|array
     */
    protected static function processIniConfig(string $iniFile, string $jsonFile): ?array
    {
        $appConfig = parse_ini_file($iniFile, true, INI_SCANNER_TYPED);
        $max = count($appConfig);
        $index = '';
        $config = $keys = $moduleInfo = $error = [];
        $i = 0;

        if (!$appConfig || !$max) {
            return ['malformed config file', Response::FATAL_INTERNAL_ERROR];
        }

        $keys = array_keys($appConfig);

        while($i < $max) {
            $index = $keys[$i];
            $config = $appConfig[$index];

            if (empty($config['actions'])) {
                self::$data[$index] = $config;
            } else {
                if ($error = self::processIniModule($config, $moduleInfo)) {
                    $i = $max;
                } else {
                    self::$modules[$index] = $moduleInfo;
                }
            }

            ++$i;
        }

        if ($error) {
            return ["Error processing {$error[0]}", $error[1]];
        }

        if (!file_put_contents($jsonFile, json_encode([self::$data, self::$modules]))) {
            return ['Error saving config.json', Response::FATAL_INTERNAL_ERROR];
        }

        return null;
    }

    /**
     * @param array $config
     * @param array $moduleInfo
     * @return null|array
     */
    protected static function processIniModule(array $config, array &$moduleInfo): ?array
    {
        if (empty($config['actions'])) {
            return ['actions does not exist on config', Response::FATAL_INTERNAL_ERROR];;
        }

        if (!is_string($config['actions'])) {
            return ['actions is not an string', Response::FATAL_INTERNAL_ERROR];;
        }

        $dummy = preg_replace("/\s+/", "", $config['actions']);

        $moduleInfo = [
            'actions' => explode(',', $dummy)
        ];

        if (isset($config['auth'])) {
            $dummy = preg_replace("/\s+/", "", $config['auth']);
            $moduleInfo['auth'] = explode(',', $dummy);
        }

        if (isset($config['cli'])) {
            $dummy = preg_replace("/\s+/", "", $config['cli']);
            $moduleInfo['cli'] = explode(',', $dummy);
        }

        return null;
    }
}
