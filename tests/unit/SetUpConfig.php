<?php


namespace Nap\Tests;

trait SetUpConfig
{
    private string $iniFile;
    private string $jsonFile;

    protected function SetUpConfig(): void
    {
        $this->iniFile = ROOT_DIR . 'tests/config/config.ini';
        $this->jsonFile = ROOT_DIR . 'tests/config/config.json';

        if(!file_exists($this->iniFile)) {
            $exampleFile = $this->iniFile . '.example';

            if (!file_exists($exampleFile)){
                throw new Exception('No config file present');
            }

            copy($exampleFile, $this->iniFile);
        }

        if (file_exists($this->jsonFile)) {
            unlink($this->jsonFile);
        }
    }
}