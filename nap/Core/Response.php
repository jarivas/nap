<?php
namespace Nap;

class Response {
    
    const OK_TYPE_CREATED = 201;
    const OK_TYPE_ACCEPTED = 202;
    const OK_TYPE_NO_CONTENT = 204;
    const WARNING_TYPE_BAD_REQUEST = 400;
    const WARNING_TYPE_UNAUTHORIZED = 401;
    const WARNING_TYPE_FORBIDEN = 403;
    const WARNING_TYPE_NOT_FOUND = 404;
    const WARNING_TYPE_METHOD_NOT_ALLOWED = 405;
    const WARNING_TYPE_CONFLICT= 409;
    const WARNING_TYPE_LOCKED = 423;

    public static function ok($response) {
        self::helper(200, json_encode($response));
    }
    
    public static function okEmpty(int $ok_type){
        http_response_code($ok_type);
        die;
    }
    
    public static function okHelper($result){
        global $appConfig;
        
        if($appConfig['system']['debug'])
            self::ok($result);
        else
            self::okEmpty(Response::OK_TYPE_NO_CONTENT);
    }
    
    public static function error(string $message) {
        self::helper(500, json_encode(['message' => $message]));
    }
    
    public static function warning(int $warning_type, string $message) {
        self::helper($warning_type, json_encode(['message' => $message]));
    }
    
    protected static function helper(int $code, string $message){
        header("Content-type: application/json; charset=utf-8");
        http_response_code($code);
        die($message);
    }
}
