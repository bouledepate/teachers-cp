<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 */

class AuthAssignment extends ActiveRecord
{
    public function rules()
    {
        return [
            'required' => [['item_name', 'user_id', 'created_at'], 'required'],
            'string' => [['item_name', 'user_id'], 'string', 'max' => 64],
            'integer' => ['created_at', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'item_name' => 'Роль',
            'user_id' => 'ID пользователя',
            'created_at' => 'Создано'
        ];
    }

    public static function getRoleById($id)
    {
        return static::findOne($id)->item_name;
    }
}