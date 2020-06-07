<?php


namespace Core;


class Request
{
    private static $data;

    public static function init()
    {
        $body = file_get_contents('php://input');

        if (strlen($body) > 0) {
            $request = json_decode($body, true);

            if (!$request) {
                Logger::info($body);
                Response::warning(Response::WARNING_BAD_REQUEST, 'JSON not well formed');
            }

            if (empty($request['module'])) {
                Response::warning(Response::WARNING_BAD_REQUEST, 'Module is required');
            }

            if (empty($request['action'])) {
                Response::warning(Response::WARNING_BAD_REQUEST, 'Action is required');
            }

            if (empty($request['parameters'])) {
                $request['parameters'] = [];
            }

            self::$data = $request;

            self::exeModuleAction();
        } else {
            Response::warning(Response::WARNING_BAD_REQUEST, 'Empty body');
        }
    }

    private static function exeModuleAction()
    {
        $module = self::$data['module'];
        $action = self::$data['action'];

        if (!Configuration::validateModuleAction($module, $action)) {
            Response::warning(Response::WARNING_BAD_REQUEST, 'Wrong Module and/or Action');
        }

        if (Configuration::shouldAuth($module, $action)) {
            if (!Authentication::isValid(self::$data['parameters'], getallheaders())) {
                Response::warning(Response::WARNING_UNAUTHORIZED, 'Wrong login credentials');
            }
        }

        $module = ucfirst($module);
        $action = ucfirst($action);

        $module = "Modules\\$module\\$action::process";

        Response::ok($module(self::$data['parameters']));
    }
}
