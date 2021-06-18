<?php


namespace Nap\Tests;

trait SetUpConfig
{
    private static string $iniFile;
    private static string $jsonFile;

    protected static function SetUpConfig(): void
    {
        self::$iniFile = ROOT_DIR . 'tests/config/config.ini';
        self::$jsonFile = ROOT_DIR . 'tests/config/config.json';

        if(!file_exists(self::$iniFile)) {
            $exampleFile = self::$iniFile . '.example';

            if (!file_exists($exampleFile)){
                throw new Exception('No config file present');
            }

            copy($exampleFile, self::$iniFile);
        }

        if (file_exists(self::$jsonFile)) {
            unlink(self::$jsonFile);
        }
    }
}