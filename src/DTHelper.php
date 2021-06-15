<?php


namespace Nap;

use Exception;
use Nap\Configuration\ConfigHelper;
use DateTime;
use DateTimeZone;


class DTHelper
{
    use ConfigHelper;

    protected static string $dateTimeFormat = 'Y.m.d H:i:s';
    protected static string $dateTimeZone = 'Europe/Berlin';
    protected static DateTimeZone $dtZone;

    public static function init(): ?array
    {
        if(empty(self::$config)) {
            return ['DTHelper config problem', Response::FATAL_INTERNAL_ERROR];
        }

        if(!self::setDateTimeFormat(self::$config['date_time_format'])) {
            return ['DTHelper date_time_format problem', Response::FATAL_INTERNAL_ERROR];
        }

        if(!self::setDateTimeZone(self::$config['date_time_zone'])) {
            return ['DTHelper date_time_zone problem', Response::FATAL_INTERNAL_ERROR];
        }

        return null;
    }

    /**
     * @param string $dtFormat
     * @return bool
     */
    public static function setDateTimeFormat(string $dtFormat): bool
    {
        if(empty($dtFormat)) {
            return false;
        }

        if (self::$dateTimeFormat === $dtFormat) {
            return true;
        }

        self::$dateTimeFormat = $dtFormat;

        return true;
    }

    /**
     * @return string
     */
    public static function getDateTimeFormat(): string
    {
        return self::$dateTimeFormat;
    }

    /**
     * @param string $dtZone
     * @return bool
     */
    public static function setDateTimeZone(string $dtZone): bool
    {
        $result = false;

        if(empty($dtZone)) {
            return false;
        }

        if (self::$dateTimeZone === $dtZone) {
            return true;
        }

        try {
            self::$dtZone = new DateTimeZone($dtZone);
            self::$dateTimeZone = $dtZone;
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @return string
     */
    public static function getDateTimeZone(): string
    {
        return self::$dateTimeZone;
    }

    /**
     * @param string $value
     * @return DateTime|bool
     */
    public static function getDate(string $value): ?DateTime
    {
        $dt = DateTime::createFromFormat(DTHelper::$dateTimeFormat, $value, self::$dtZone);

        return $dt ?: null;
    }

}