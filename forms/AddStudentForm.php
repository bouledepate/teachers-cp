<?php


namespace app\forms;

use yii\base\Model;
use app\models\User;


class AddStudentForm extends Model
{
    public $groupId;
    public $username;

    public function rules()
    {
        return [
            [['username', 'groupId'], 'required', 'message' => 'Поля должны быть заполнены'],
            ['username', 'string', 'max' => 255],
            ['username', 'validateRole']
        ];
    }

    public function validateRole($attribute, $params)
    {
        $user = User::findByUsername($this->$attribute);
        if(!\Yii::$app->authManager->getAssignment('student', $user->id)){
            $this->addError($attribute, 'Пользователь должен быть определён как студент');
        }
    }
}