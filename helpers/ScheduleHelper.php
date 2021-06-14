<?php


namespace app\helpers;

use app\models\Schedule;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;


class ScheduleHelper
{
    public static function weekList(): array
    {
        return [
            Schedule::WEEK_NUM => 'Числитель',
            Schedule::WEEK_DENOM => 'Знаменатель'
        ];
    }

    public static function dayList(): array
    {
        return [
            Schedule::DAY_MONDAY => 'Понедельник',
            Schedule::DAY_TUESDAY => 'Вторник',
            Schedule::DAY_WEDNESDAY => 'Среда',
            Schedule::DAY_THURSDAY => 'Четверг',
            Schedule::DAY_FRIDAY => 'Пятница',
            Schedule::DAY_SATURDAY => 'Суббота'
        ];
    }

    public static function timeList(): array
    {
        return [
            Schedule::TIME_FIRST => '09:00 - 10:40',
            Schedule::TIME_SECOND => '11:00 - 12:40',
            Schedule::TIME_THIRD => '13:00 - 14:40',
            Schedule::TIME_FOURTH => '15:00 - 16:40',
            Schedule::TIME_FIFTH => '17:00 - 18:40'
        ];
    }

    public static function weekName($week)
    {
        return ArrayHelper::getValue(self::weekList(), $week);
    }

    public static function dayName($day)
    {
        return ArrayHelper::getValue(self::dayList(), $day);
    }

    public static function timeName($time)
    {
        return ArrayHelper::getValue(self::timeList(), $time);
    }

    public static function checkScheduleByWeekType($week, $group){
        $schedule = Schedule::findAll(['week' => $week, 'group_id' => $group ]);
        if($schedule){
            return Html::tag('span', 'Установлено', ['class' => 'text-success']);
        } else {
            return Html::tag('span', 'Не установлено', ['class' => 'text-danger']);
        }
    }

    public static function checkDisciplinesByDay($data, $day){
        foreach ($data as $dataObject){
            if($dataObject->day === $day){
                return null;
            }
        }
        return Html::tag('span', '(Занятий нет)', ['class' => 'text-muted h6']);
    }

    public static function displayDiscipline($data, $day, $time){
        foreach($data as $dataObject){
            if($dataObject->day === $day && $dataObject->time === $time){
                return $dataObject->discipline->name;
            }
        }
    }

    public static function displayDisciplineTeacher($data, $day, $time){
        foreach($data as $dataObject){
            if($dataObject->day === $day && $dataObject->time === $time){
                $items = [];
                foreach ($dataObject->discipline->users as $user) {
                    if($user->role === 'teacher'){
                        $fullName = $user->profile->getFullName();
                        $items[] = Html::a($fullName, ['profile/index', 'username'=>$user->username]);
                    }
                }
                return $items ? implode(', ', $items) : null;
            }
        }
    }

}