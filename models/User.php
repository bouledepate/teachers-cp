<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\web\HttpException;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $status
 * @property int $group_id
 * @property string|null $access_token
 * @property-read string $passwordHash
 * @property-read null|string $authKey
 * @property-read string $role
 * @property string|null $auth_key
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 0;


    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required'],
            [['id', 'status'], 'integer'],
            [['username', 'password' ], 'string', 'max' => 255],
            [['access_token', 'auth_key'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'email' => 'Email',
            'access_token' => 'Токен',
            'auth_key' => 'Ключ авторизации',
            'status' => 'Статус аккаунта',
            'group_id' => 'Группа'
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function create($model)
    {
        $user = new User();
        $user->username = $model->username;
        $user->email = $model->email;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($model->password);
        $user->generateAuthKey();
        $user->save();
        $user->setRole($model->role);
        return $user->save() ? $user : null;
    }

    public static function updateUser($id, $params)
    {
        $user = User::findOne($id);
        $user->username = $params->username;
        $user->setPassword($params->password);
        $user->email = $params->email;
        $user->save();
        $user->setRole($params->role);
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    public function setGroup($id)
    {
        if($id === 0){
            $this->group_id = null;
        } else {
            $this->group_id = $id;
        }
        $this->save();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setRole($role_id)
    {
        $auth = Yii::$app->authManager;
        if($role_id !== ''){
            if($role_id === '1'){
                $role = 'admin';
            } elseif($role_id === '2'){
                $role = 'teacher';
            } else {
                $role = 'student';
            }
            $auth->revokeAll($this->id);
            $auth->assign($auth->getRole($role), $this->id);
        }
    }

    public function getRole()
    {
        $auth = Yii::$app->authManager;
        if($auth->getAssignment('admin', $this->id)){
            return 'Администратор';
        } elseif($auth->getAssignment('teacher', $this->id)) {
            return 'Преподаватель';
        } else {
            return 'Студент';
        }
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString(15).'-token';
    }

    public function changeStatus()
    {
        if($this->status === User::STATUS_ACTIVE){
            $this->status = User::STATUS_BLOCKED;
        } else {
            $this->status = User::STATUS_ACTIVE;
        }
        $this->save();
    }
}
