<?php

namespace app\helpers;

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function statusList()
    {
        return [
            User::STATUS_BLOCKED => 'Заблокирован',
            User::STATUS_ACTIVE => 'Активен'
        ];
    }

    public static function roleList()
    {
        return [
            'admin' => 'Администратор',
            'teacher' => 'Преподаватель',
            'student' => 'Студент'
        ];
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function roleName($role)
    {
        return ArrayHelper::getValue(self::roleList(), $role);
    }

    public static function roleLabel($role)
    {
        switch ($role) {
            case 'admin':
                $class = 'badge badge-danger';
                break;
            case 'teacher':
                $class = 'badge badge-primary';
                break;
            case 'student':
                $class = 'badge badge-secondary';
                break;
        }

        return Html::tag('span', ArrayHelper::getValue(self::roleList(), $role), [
            'class' => $class
        ]);
    }

    public static function statusLabel($status)
    {
        switch ($status) {
            case User::STATUS_BLOCKED:
                $class = 'badge badge-pill badge-danger';
                break;
            case User::STATUS_ACTIVE:
                $class = 'badge badge-pill badge-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}