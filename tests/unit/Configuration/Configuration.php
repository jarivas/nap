<?php


namespace Nap\Tests\Configuration;

use Nap\Configuration\Configuration as ConfigurationNap;

class Configuration extends ConfigurationNap
{
    public static function processIniConfig(string $iniFile, string $jsonFile): ?array
    {
        return parent::processIniConfig($iniFile, $jsonFile);
    }

    public static function processIniModule(array $config, array &$moduleInfo): ?array
    {
        return parent::processIniModule($config, $moduleInfo);
    }
}