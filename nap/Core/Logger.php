<?php
namespace Nap;

class Logger {
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    public static function debug(string $message) {
        log_writer(self::DEBUG, $message);
    }

    public static function info(string $message) {
        log_writer(self::INFO, $message);
    }

    public static function notice(string $message) {
        log_writer(self::NOTICE, $message);
    }

    public static function warning(string $message) {
        log_writer(self::WARNING, $message);
    }

    public static function error(string $message) {
        log_writer(self::ERROR, $message);
    }

    public static function critical(string $message) {
        log_writer(self::CRITICAL, $message);
    }

    public static function alert(string $message) {
        log_writer(self::ALERT, $message);
    }

    public static function emergency(string $message) {
        log_writer(self::EMERGENCY, $message);
    }
}
