<?php


namespace Core;


use Core\Db\Persistence;

class Request
{
    private static $data;

    public static function init()
    {
        $body = file_get_contents('php://input');
        $className = '';
        $request = $persistence = null;
        $parameters = [];

        if (!strlen($body) {
            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Empty body');
        }

        $request = json_decode($body, true);

        if (!$request) {
            Logger::info($body);

            self::sendWarning(Response::WARNING_BAD_REQUEST, 'JSON not well formed');
        }

        if (empty($request['module'])) {
            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Module is required');
        }

        if (empty($request['action'])) {
            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Action is required');
        }

        if (empty($request['parameters'])) {
            $request['parameters'] = [];
        }

        self::$data = $request;

        $className = self::getModuleAction();

        $parameters = Sanitize::process(self::$data['module'], self::$data['action'], self::$data['parameters']);

        $persistence = self::getPersistence();

        Response::ok($className($parameters, $persistence));
    }

    private static function sendWarning(int $type, string $msg)
    {
        Logger::warning($msg);
        Response::warning($type, $msg);
    }

    private static function getModuleAction()
    {
        $module = self::$data['module'];
        $action = self::$data['action'];

        if (!Configuration::validateModuleAction($module, $action)) {
            self::sendWarning(Response::WARNING_BAD_REQUEST, 'Wrong Module and/or Action');
        }

        if (Configuration::shouldAuth($module, $action)) {
            if (!Authentication::isValid(self::$data['parameters'], getallheaders())) {
                self::sendWarning(Response::WARNING_UNAUTHORIZED, 'Wrong login credentials');
            }
        }

        $module = ucfirst($module);
        $action = ucfirst($action);

        return "Modules\\$module\\$action::process";
    }

    private static function getPersistence(): Persistence
    {
        $db = Configuration::getData('db');

        $className = "Core\\Db\\{$db['type']}::getInstance";

        return $className($db);
    }
}
