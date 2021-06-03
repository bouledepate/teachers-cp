<?php


namespace app\forms;

use yii\base\Model;


class CreateDisciplineForm extends Model
{
    public $name;
    public $teacherId;

    public static function className()
    {
        return 'CreateDisciplineForm';
    }

    public function rules()
    {
        return [
            'required' => ['name', 'required'],
            'string' => ['name', 'string', 'max' => 255],
        ];
    }
}