<?php

namespace Core;

class Response
{
    const OK_DEFAULT = 200;
    const OK_CREATED = 201;
    const OK_ACCEPTED = 202;
    const OK_NO_CONTENT = 204;
    const WARNING_BAD_REQUEST = 400;
    const WARNING_UNAUTHORIZED = 401;
    const WARNING_FORBIDDEN = 403;
    const WARNING_NOT_FOUND = 404;
    const WARNING_METHOD_NOT_ALLOWED = 405;
    const WARNING_CONFLICT = 409;
    const WARNING_LOCKED = 423;
    const FATAL_INTERNAL_ERROR = 500;

    protected static function helper(int $code, string $message)
    {        
        header("Content-type: application/json; charset=utf-8");

        http_response_code($code);

        die($message);
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

    public static function error(string $message)
    {
        self::helper(self::FATAL_INTERNAL_ERROR, json_encode(['message' => $message]));
    }

    public static function warning(int $warning_type, string $message)
    {
        self::helper($warning_type, json_encode(['message' => $message]));
    }
}
