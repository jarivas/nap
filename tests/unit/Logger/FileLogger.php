<?php


namespace Nap\Tests\Logger;


use Nap\Logger;
use Nap\Configuration\ConfigHelper;

class FileLogger extends Logger
{
    use ConfigHelper;

    protected static string $logDir = '';

    protected static bool $debug;

    public static function init(string $dir, bool $debug = true): bool
    {
        $result = false;

        if (empty(self::$logDir)) {

            parent::setRequestId(uniqid('REQUEST_'));

            if ($result = self::createPath($dir)) {
                self::$logDir = $dir . DIRECTORY_SEPARATOR;

                self::$debug = $debug;

                $result = true;
            }
        } else {
            $result = true;
        }

        return $result;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        if(defined(__CLASS__ . '::' . strtoupper($name))) {
            return self::log($name, $arguments[0]);
        }

        return false;
    }

    protected static function log(string $level, string $message): bool
    {
        $result = false;

        if (file_exists(self::$logDir)) {
            $fileName = self::$logDir . $level . '.log';
            $microTime = explode(' ', microtime());
            $microTime = date('Y-m-d h:i:s.') . $microTime[1];
            $message = PHP_EOL . $microTime . ': [' . parent::getRequestId() . '] ' . $message;

            if (self::$debug || $level === Logger::CRITICAL
                || $level === Logger::ERROR || $level === Logger::EMERGENCY) {
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 7);

                $trace = array_slice($trace, 2);

                $message .= PHP_EOL . print_r($trace, true);
            }

            if (file_put_contents($fileName, $message, FILE_APPEND | LOCK_EX)) {
                $result = true;
            }
        }

        return $result;
    }

    protected static function createPath($path): bool
    {
        if (is_dir($path)) {
            return true;
        }

        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);

        return self::createPath($prev_path) && is_writable($prev_path) && mkdir($path);
    }
}