<?php

namespace Core;

class Sanitize
{
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param string $module
     * @param string $action
     * @param array $parameters
     * @return array
     */
    public static function process(string $module, string $action, array &$parameters): array
    {
        $preKey = "{$module}_{$action}_";
        $rules = self::getRules($preKey);
        $parameterName = '';
        $errors = [];
        $value = null;

        foreach ($rules as $key => $filters) {
            $parameterName = str_replace($preKey, '', $key);

            self::applyFilters($filters, $parameterName, $parameters, $errors);
        }

        if (count($errors)) {
            $msg = self::getErrorMsg($errors);

            return [false, $msg];
        }

        return [true, ""];
    }

    protected static function getRules(string $preKey)
    {
        $sanitize = Configuration::getData('sanitize');

        $result = [];
        $length = strlen($preKey);

        foreach ($sanitize as $key => $value) {
            if (substr($key, 0, $length) === $preKey) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    protected static function applyFilters(string $filters, string $parameterName, array &$parameters, array &$errors)
    {
        $filtersError = [];

        if (empty($parameters[$parameterName])) {
            if (strpos($filters, 'REQUIRED') !== false) {
                $filtersError[] = 'Required';
            }
        } else {
            $value = $parameters[$parameterName];

            foreach (explode('|', $filters) as $filter) {
                self::applyFilter($filter, $filtersError, $value);
            }
        }

        if (count($filtersError)) {
            $errors[$parameterName] = $filtersError;
        }
    }

    protected static function applyFilter(string $filter, array &$filtersError, $value)
    {
        $pieces = explode('_', $filter);

        switch ($pieces[0]) {
            case 'DEFAULT':
                if (!$value || !strlen($value)) {
                    $value = $pieces[1];
                }
                break;
            case 'REQUIRED':
                if (!$value) {
                    $filtersError[] = 'Required';
                }
                break;
            case 'DATETIME':
                $value = \DateTime::createFromFormat(self::DATETIME_FORMAT, $value);

                if (!$value) {
                    $filtersError[] = 'Wrong format, the right one is: ' . self::DATETIME_FORMAT;
                }
                break;
            case 'FILTER':
                if ($pieces[1] === 'SANITIZE') {
                    $value = filter_var($value, constant($filter));

                    if ($pieces[3] === 'INT') {
                        $value = intval($value);
                    }
                }

                if ($pieces[1] === 'VALIDATE') {
                    if (!filter_var($value, constant($filter))) {
                        $filtersError[] = 'Invalid';
                    } else {
                        if ($pieces[3] === 'INT') {
                            $value = intval($value);
                        }

                        if ($pieces[3] === 'FLOAT') {
                            $value = floatval($value);
                        }
                    }
                }
                break;
        }
    }

    protected static function getErrorMsg(array &$error): string
    {
        $result = '';

        foreach ($error as $param => $errors) {
            $result .= $param . ': ' . implode(', ', $errors) . ' | ';
        }

        return $result;
    }
}
