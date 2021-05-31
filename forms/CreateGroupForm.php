<?php


namespace app\forms;

use yii\base\Model;

class CreateGroupForm extends Model
{
    public $name;

    public function rules()
    {
        return [
            'require' => ['name', 'required'],
            'length' => ['name', 'string', 'max' => 255]
        ];
    }
}