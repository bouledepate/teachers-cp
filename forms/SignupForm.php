<?php

namespace app\forms;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Console;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $role;
    public $email;

    public $items = [
        'admin' => 'Администратор',
        'teacher' => 'Преподаватель',
        'student' => 'Студент'
    ];
    public $params = [
        'prompt' => 'Выберите роль...',
    ];

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Данное имя пользователя уже занято'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Данный почтовый адрес уже используется'],

            [['password', 'password_repeat'], 'required'],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],

            ['role', 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'password_repeat' => 'Повторение пароля',
            'role' => 'Роль пользователя',
            'email' => 'Электронная почта'
        ];
    }
}