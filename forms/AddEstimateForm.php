<?php


namespace app\forms;


use app\models\Estimate;
use app\models\User;

class AddEstimateForm extends \yii\base\Model
{
    public $authorId;
    public $createdAt;
    public $value;
    public $userId;
    public $disciplineId;

    public function rules()
    {
        return [
            'required' => [['userId', 'disciplineId', 'authorId', 'value'], 'required'],
            'integer' => [['authorId', 'disciplineId', 'userId', 'value'], 'integer'],
            'date' => ['createdAt', 'date', 'format' => 'php:Y-m-d']
        ];
    }

    public function attributeLabels()
    {
        return [
            'authorId' => 'Преподаватель',
            'userId' => 'Студент',
            'value' => 'Баллы',
            'disciplineId' => 'Дисцилпина',
            'createdAt' => 'Выставлено'
        ];
    }
}