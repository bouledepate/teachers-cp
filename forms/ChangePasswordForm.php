<?php


namespace app\forms;

use yii\base\Model;


class ChangePasswordForm extends Model
{
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            'require' => [['password', 'password_repeat'], 'required', 'message' => 'Поля должны быть заполнены'],
            'compare' => ['password', 'compare',  'compareAttribute' => 'password_repeat', 'message' => 'Пароли должны совпадать'],
        ];
    }
}