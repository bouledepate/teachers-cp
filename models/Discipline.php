<?php


namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * @property int $id
 * @property string $name
 */
class Discipline extends ActiveRecord
{
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

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])
            ->viaTable('group_discipline', ['discipline_id' => 'id']);
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

    public function removeTeacher($id)
    {
        $user = User::findOne(['id' => $id]);
        $this->unlink('users', $user);
    }

    // Queries
    public static function getDisciplinesByGroup($id)
    {
        return Discipline::find()
            ->where(['not in', 'discipline.id', (new Query())
                ->select('discipline.id')
                ->from('discipline')
                ->leftJoin('group_discipline', 'group_discipline.discipline_id=discipline.id')
                ->where(['group_discipline.group_id' => $id])])
            ->asArray()
            ->all();
    }
}