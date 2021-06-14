<?php


namespace app\models;

/**
 * @property int $id,
 * @property int $group_id
 * @property int $discipline_id
 * @property int $day
 * @property int $week
 * @property int $time
 *
 */

class Schedule extends \yii\db\ActiveRecord
{
    const WEEK_NUM = 0;
    const WEEK_DENOM = 1;

    const DAY_MONDAY = 0;
    const DAY_TUESDAY = 1;
    const DAY_WEDNESDAY = 2;
    const DAY_THURSDAY = 3;
    const DAY_FRIDAY = 4;
    const DAY_SATURDAY = 5;

    const TIME_FIRST = 0;
    const TIME_SECOND = 1;
    const TIME_THIRD = 2;
    const TIME_FOURTH = 3;
    const TIME_FIFTH = 4;

    public function rules(): array
    {
        return [
            'required' => [['discipline_id', 'group_id'], 'required'],
            'integer' => [['group_id', 'discipline_id', 'day', 'time', 'week'], 'integer'],
            'time' => ['time', 'validateTime']
        ];
    }

    public static function tableName(): string
    {
        return 'schedule';
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'group_id' => 'Группа',
            'discipline_id' => 'Дисциплина',
            'day' => 'День недели',
            'week' => 'Неделя',
            'time' => 'Время проведения'
        ];
    }

    public function getGroup(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Group::class, ['id'=>'group_id']);
    }

    public function getDiscipline(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Discipline::class, ['id' => 'discipline_id']);
    }

    public static function create($model, $returned=false)
    {
        $object = new Schedule();
        $object->group_id = $model->groupId;
        $object->week = $model->week;
        $object->day = $model->day;
        $object->time = $model->time;
        $object->discipline_id = $model->disciplineId;
        $object->save();

        if($returned){
            return $object;
        }
    }

    public function validateTime($attribute, $params)
    {
        $schedule = Schedule::findOne([
            'group_id' => $this->group_id,
            'week' => $this->week,
            'time' => $this->time,
            'day' => $this->day
        ]);

        if($schedule){
            $this->addError($attribute, 'У данной группы уже есть занятие в это время.');
        }
    }
}