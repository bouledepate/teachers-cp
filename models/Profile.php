<?php


namespace app\models;


use app\forms\UploadImageForm;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $avatar
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

    public static function create($user_id)
    {
        $profile = new Profile();
        $profile->user_id = $user_id;
        $profile->save();
    }

    public static function updateProfile($id, $params)
    {
        $profile = Profile::findOne($id);
        $profile->first_name = $params->firstName;
        $profile->last_name = $params->lastName;
        $profile->save();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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

    public function getImage()
    {
        return ($this->avatar) ? '/images/' . $this->avatar : '/images/' . 'no-avatar.png';
    }

    public function saveImage($filename)
    {
        $this->avatar = $filename;
        return $this->save(false);
    }

    public function deleteImage()
    {
        $imageUploadModel = new UploadImageForm();
        $imageUploadModel->deleteCurrentImage($this->image);
    }
}