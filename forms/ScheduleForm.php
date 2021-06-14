<?php


namespace app\forms;

use app\models\Schedule;


class ScheduleForm extends \yii\base\Model
{
    public $groupId;
    public $disciplineId;
    public $week;
    public $day;
    public $time;

    public function rules()
    {
        return [
            'required' => [['groupId', 'week', 'disciplineId'], 'required'],
            'integer' => [['groupId', 'disciplineId', 'week', 'time', 'day'], 'integer'],
            'time' => ['time', 'validateTime']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groupId' => 'Группа',
            'disciplineId' => 'Дисциплина',
            'disciplines' => 'Дисциплины',
            'week' => 'Тип недели',
            'day' => 'День недели',
            'time' => 'Время проведения'
        ];
    }

    public function validateTime($attribute, $params)
    {
        $schedule = Schedule::findOne([
            'group_id' => $this->groupId,
            'week' => $this->week,
            'time' => $this->time,
            'day' => $this->day
        ]);

        if($schedule){
            \Yii::$app->session->setFlash('danger', 'У данной группы уже есть занятие в это время.');
            $this->addError($attribute, 'У данной группы уже есть занятие в это время.');
        }
    }
}