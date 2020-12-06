<?php

namespace Api;

use Core\Configuration as CoreConfig;

trait Logger
{
    protected static $logDir = false;

    protected static function write(string $level, string $message): bool
    {
        $result = false;

        if (self::init()) {
            $fileName = self::$logDir . $level . '.log';
            $microTime = explode(' ', microtime());
            $microTime = date('Y-m-d h:i:s.') . $microTime[1];
            $message = PHP_EOL . $microTime . ': [' . self::$requestId . '] ' . $message;
            $system = CoreConfig::getData('system');

            if ($system['debug'] || $level === Logger::CRITICAL
                    || $level === Logger::ERROR || $level === Logger::EMERGENCY) {
                
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 7);
                
                $trace = array_slice($trace, 2);
                
                $message .= PHP_EOL . print_r($trace, true);
            }

            $result = file_put_contents($fileName, $message, FILE_APPEND | LOCK_EX);
        }

        return $result;
    }

    protected static function init(): bool
    {
        $result = false;

        if (!self::$logDir) {
            $logDir = ROOT_DIR . 'log';
            $result = self::createPath($logDir);

            if ($result) {
                self::$logDir = $logDir . DIRECTORY_SEPARATOR;
            }
        } else {
            $result = true;
        }

        return $result;
    }

    protected static function createPath($path)
    {
        if (is_dir($path)) {
            return true;
        }

        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);

        $return = self::createPath($prev_path);

        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }
}
