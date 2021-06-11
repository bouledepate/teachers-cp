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
            'date' => ['createdAt', 'date', 'format' => 'php:Y-m-d'],
            'value' => ['value', 'validateValue']
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



    public function validateValue($attribute, $params)
    {
        $userDiscipline = User::getUserDisciplineRelationId($this->userId, $this->disciplineId);
        $objects = Estimate::findAll(['user_discipline_id' => $userDiscipline->id]);
        $total = (int)$this->$attribute;

        foreach($objects as $object){
            $total += $object->value;
        }

        if($total > 100){
            \Yii::$app->session->setFlash('danger', 'Вы не можете выставить более 100 баллов этому студенту.');
            $this->addError($attribute, 'Вы не можете выставить более 100 баллов этому студенту.');
        }
    }

}