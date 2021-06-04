<?php


namespace app\forms;


class AddDisciplineForm extends \yii\base\Model
{
    public $disciplineId;

    public function rules()
    {
        return [
            [['disciplineId'], 'required', 'message' => 'Вы должны выбрать как минимум 1-у дисциплину'],
        ];
    }
}