<?php

namespace Core;

class Logger
{
    use \Api\Logger;

    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    protected static $requestId;
    
    public static function debug(string $message): bool
    {
        return self::write(self::DEBUG, $message);
    }

    public static function info(string $message): bool
    {
        return self::write(self::INFO, $message);
    }

    public static function notice(string $message): bool
    {
        return self::write(self::NOTICE, $message);
    }

    public static function warning(string $message): bool
    {
        return self::write(self::WARNING, $message);
    }

    public static function error(string $message): bool
    {
        return self::write(self::ERROR, $message);
    }

    public static function critical(string $message): bool
    {
        return self::write(self::CRITICAL, $message);
    }

    public static function alert(string $message): bool
    {
        return self::write(self::ALERT, $message);
    }

    public static function emergency(string $message): bool
    {
        return self::write(self::EMERGENCY, $message);
    }

    public static function setRequestId(string $requestId)
    {
        self::$requestId = $requestId;
    }

    public static function canLog(): bool
    {
        return self::init();
    }
}
