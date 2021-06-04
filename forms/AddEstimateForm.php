<?php


namespace app\forms;


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
            'date' => ['createdAt', 'date'],
        ];
    }
}