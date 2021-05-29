<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property-read mixed $user
 * @property-read string $fullName
 * @property-read string $firstName
 * @property-read string $lastName
 * @property string $last_name
 */

class Profile extends ActiveRecord
{
    public static function tableName()
    {
        return 'profile';
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'trim'],
            ['user_id', 'required'],
            ['user_id', 'unique'],
            [['id', 'user_id'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID пользователя',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия'
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return User::findOne(['id' => $this->user_id]);
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}