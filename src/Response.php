<?php

namespace Nap;

/**
 * @codeCoverageIgnore
 */
class Response
{
    public const OK_DEFAULT = 200;
    public const OK_CREATED = 201;
    public const OK_ACCEPTED = 202;
    public const OK_NO_CONTENT = 204;
    public const WARNING_BAD_REQUEST = 400;
    public const WARNING_UNAUTHORIZED = 401;
    public const WARNING_FORBIDDEN = 403;
    public const WARNING_NOT_FOUND = 404;
    public const WARNING_METHOD_NOT_ALLOWED = 405;
    public const WARNING_CONFLICT = 409;
    public const WARNING_LOCKED = 423;
    public const FATAL_INTERNAL_ERROR = 500;

    public static function process(array $response)
    {
        if ($response[0] === 'ok') {
            self::ok($response[1]);
        }

        if ($response[0] === 'okEmpty') {
            self::okEmpty($response[1]);
        }

        if ($response[0] === 'warning') {
            self::warning($response[1], $response[2]);
        }
    }

    public static function ok(array $response)
    {
        self::helper(self::OK_DEFAULT, json_encode($response));
    }

    public static function okEmpty(int $ok_type)
    {
        http_response_code($ok_type);
        die;
    }

    public static function warning(string $message, int $warning_type)
    {
        self::helper($warning_type, json_encode(['message' => $message]));
    }

    protected static function helper(int $code, string $message)
    {
        header("Content-type: application/json; charset=utf-8");

        http_response_code($code);

        die($message);
    }
}
