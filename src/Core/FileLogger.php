<?php


namespace Core;


trait FileLogger
{
    private static $logDir = false;

    private static function write(string $level, string $message)
    {
        $result = false;

        if (self::init()) {
            $fileName = self::$logDir . $level . '.log';
            $microTime = explode(' ', microtime());
            $microTime = date('Y-m-d h:i:s.') . $microTime[1];
            $message =  $microTime . ': [' . self::$requestId. '] ' . $message;

            if ($level === Logger::CRITICAL || $level === Logger::ERROR || $level === Logger::EMERGENCY) {
                $message .= PHP_EOL . print_r(debug_backtrace(), 1);
;            }

            $result = file_put_contents($fileName, $message, FILE_APPEND | LOCK_EX);
        }

        return $result;
    }

    private static function init(): bool
    {
        $result = false;

        if(!self::$logDir) {
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

    private static function createPath($path) {
        if (is_dir($path)) return true;

        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );

        $return = self::createPath($prev_path);

        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }
}
