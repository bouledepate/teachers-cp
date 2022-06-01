<?php


namespace app\forms;

use yii\base\Model;

class CreateGroupForm extends Model
{
    public $name;
    public $module;
    public $speciality;

    public function rules()
    {
        return [
            'require' => [['name', 'module', 'speciality'], 'required'],
            'length' => [['name', 'module', 'speciality'], 'string', 'max' => 255]
        ];
    }
}