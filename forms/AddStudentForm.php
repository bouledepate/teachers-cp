<?php


namespace app\forms;

use yii\base\Model;


class AddStudentForm extends Model
{
    public $studentId;

    public function rules()
    {
        return [
            [['studentId'], 'required', 'message' => 'Вы должны выбрать как минимум 1-го студента'],
        ];
    }
}