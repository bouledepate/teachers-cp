<?php


namespace app\forms;

use yii\base\Model;

class CreateGroupForm extends Model
{
    public $name;
    public $speciality;

    public function rules()
    {
        return [
            'require' => [['name', 'speciality'], 'required'],
            'length' => [['name', 'speciality'], 'string', 'max' => 255]
        ];
    }
}