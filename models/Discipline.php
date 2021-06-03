<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 */
class Discipline extends ActiveRecord
{
    public $_selection;

    public static function tableName()
    {
        return 'discipline';
    }

    public function rules()
    {
        return [
            'required' => ['name', 'required'],
            'trim' => ['name', 'trim'],
            'length' => ['name', 'string', 'max' => 255],
            'id' => ['id', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование дисциплины'
        ];
    }

    public static function create($model, $returned = false)
    {
        $discipline = new Discipline();
        $discipline->name = $model->name;
        $discipline->save();
        if ($returned) {
            return $discipline;
        }
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('user_discipline', ['discipline_id' => 'id']);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTeachers($array)
    {
        foreach ($array as $key => $value) {
            $user = User::findOne(['id' => $value]);
            $this->link('users', $user);
        }
    }

    public function removeTeachers($array)
    {
        foreach ($array as $key => $value) {
            $user = User::findOne(['id' => $value]);
            $this->unlink('users', $user);
        }
    }

    public function removeTeacher($id)
    {
        $user = User::findOne(['id' => $id]);
        $this->unlink('users', $user);
    }
}