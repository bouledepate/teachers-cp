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
    public $email;

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

            ['password', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'password_repeat' => 'Повторение пароля',
            'email' => 'Электронная почта'
        ];
    }
}