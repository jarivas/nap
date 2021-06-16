<?php

namespace Nap;

/**
 * Class Logger
 * @package Nap\Logger
 * @method static bool emergency(string $message)
 * @method static bool alert(string $message)
 * @method static bool critical(string $message)
 * @method static bool error(string $message)
 * @method static bool warning(string $message)
 * @method static bool notice(string $message)
 * @method static bool info(string $message)
 * @method static bool debug(string $message)
 */
class Logger
{
    public const EMERGENCY = 'emergency';
    public const ALERT = 'alert';
    public const CRITICAL = 'critical';
    public const ERROR = 'error';
    public const WARNING = 'warning';
    public const NOTICE = 'notice';
    public const INFO = 'info';
    public const DEBUG = 'debug';

    protected static string $requestId;

    public static function setRequestId(string $requestId)
    {
        self::$requestId = $requestId;
    }

    public static function getRequestId(): string
    {
        return self::$requestId;
    }
}
