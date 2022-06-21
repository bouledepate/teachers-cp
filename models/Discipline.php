<?php


namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

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
            'required' => [['name', 'module'], 'required'],
            'trim' => [['name', 'module'], 'trim'],
            'length' => [['name', 'module'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование дисциплины',
            'module' => 'Модуль'
        ];
    }

    public static function create($model, $returned = false)
    {
        $discipline = new Discipline();
        $discipline->name = $model->name;
        $discipline->module = $model->module;
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

    public static function getDisciplines($id)
    {
        $data = Discipline::find()
            ->select(['discipline.id', 'discipline.name'])
            ->joinWith('groups')
            ->where(['group.id' => $id])
            ->asArray()
            ->all();
        return ArrayHelper::map($data, 'id', 'name');
    }

    public function deleteDiscipline()
    {
        \Yii::$app->db->transaction(function ($database) {
            \Yii::$app->db->createCommand("DELETE FROM user_discipline WHERE discipline_id = {$this->id}");
            \Yii::$app->db->createCommand("DELETE FROM group_discipline WHERE discipline_id = {$this->id}");
        });

        return $this->delete();
    }
}