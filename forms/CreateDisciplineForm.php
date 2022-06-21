<?php


namespace app\forms;

use yii\base\Model;


class CreateDisciplineForm extends Model
{
    public $name;
    public $teacherId;
    public $module;

    public static function className()
    {
        return 'CreateDisciplineForm';
    }

    public function rules()
    {
        return [
            'required' => [['name', 'module'], 'required'],
            'string' => [['name', 'module'], 'string', 'max' => 255],
        ];
    }
}