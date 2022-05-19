<?php

namespace app\enums;

class MonthEnum
{
    public const JANUARY = 1;
    public const FEBRUARY = 2;
    public const MARCH = 3;
    public const APRIL = 4;
    public const MAY = 5;
    public const JUNE = 6;
    public const JULI = 7;
    public const AUGUST = 8;
    public const SEPTEMBER = 9;
    public const OCTOBER = 10;
    public const NOVEMBER = 11;
    public const DECEMEBER = 12;

    public static function getMonths()
    {
        return [
            self::JANUARY => 'Январь',
            self::FEBRUARY => 'Февраль',
            self::MARCH => 'Март',
            self::APRIL => 'Апрель',
            self::MAY => 'Май',
            self::JUNE => 'Июнь',
            self::JULI => 'Июль',
            self::AUGUST => 'Август',
            self::SEPTEMBER => 'Сентябрь',
            self::OCTOBER => 'Октябрь',
            self::NOVEMBER => 'Ноябрь',
            self::DECEMEBER => 'Декабрь'
        ];
    }

    public static function getMonth(int $key): ?string
    {
        $months = static::getMonths();

        if (in_array($key, array_keys($months))) {
            return $months[$key];
        }

        return null;
    }
}