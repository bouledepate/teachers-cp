<?php

namespace app\helpers;

class CertificationHelper
{
    protected static array $months = [
        1 => 'Январь',
        2 => 'Февраль',
        4 => 'Март',
        8 => 'Апрель',
        16 => 'Май',
        32 => 'Июнь',
        64 => 'Июль',
        128 => 'Август',
        256 => 'Сентябрь',
        512 => 'Октябрь',
        1024 => 'Ноябрь',
        2048 => 'Декабрь'
    ];

    public static function getMonths(): array
    {
        return self::$months;
    }

    public static function getMonthByKey(int $key): ?string
    {
        if (isset(self::$months[$key])) {
            return self::$months[$key];
        }

        return null;
    }

    public static function getMonthsByKeys(int $keys): array
    {
        $result = [];

        foreach (array_keys(self::$months) as $month) {
            if (self::isSelected($keys, $month)) {
                $result[] = self::$months[$month];
            }
        }

        return $result;
    }

    public static function selectChecked(int $total): array
    {
        $result = [];

        foreach (array_keys(self::$months) as $month) {
            if (self::isSelected($total, $month)) {
                $result[] = $month;
            }
        }

        return $result;
    }

    public static function isSelected(int $total, int $value): bool
    {
        return ($total & $value) == $value;
    }
}