<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property-read string $role
 */
class Group extends ActiveRecord
{
    public $user = null;

    public function rules()
    {
        return [
            'required' => [['name'], 'required'],
            'integer' => ['id', 'integer'],
            'length' => ['name', 'string', 'max' => 255],
            'unique' => ['name', 'unique'],
            'trim' => ['name', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID группы',
            'name' => 'Название группы'
        ];
    }

    public static function create($params)
    {
        $group = new Group();
        $group->name = $params->name;
        $group->save();
    }

    public static function updateGroup($id, $params)
    {
        $group = Group::findOne(['id' => $id]);
        $group->name = $params->name;
        $group->save();
    }

    public function getDisciplines()
    {
        return $this->hasMany(Discipline::class, ['id' => 'discipline_id'])
            ->viaTable('group_discipline', ['group_id' => 'id']);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['group_id' => 'id']);
    }

    public static function addStudents($array, $groupId)
    {
        foreach ($array as $key => $value) {
            $user = User::findOne(['id' => $value]);
            $user->setGroup($groupId);
        }
    }

    public static function removeStudent($id)
    {
        $user = User::findOne(['id' => $id]);
        $user->setGroup(null);
    }

    public function addDisciplines($array)
    {
        foreach ($array as $key => $value) {
            $discipline = Discipline::findOne(['id' => $value]);
            $this->link('disciplines', $discipline);

            // Добавляем студентам группы дисциплину ID.
            foreach ($this->users as $user) {
                $user->link('disciplines', $discipline);
            }
        }
    }

    public function removeDiscipline($id)
    {
        $discipline = Discipline::findOne(['id' => $id]);
        $this->unlink('disciplines', $discipline);

        // Убираем дисциплину у студентов.
        foreach ($this->users as $user) {
            $user->unlink('disciplines', $discipline);
        }
    }

    public function hasDiscipline($dId)
    {
        return Group::find()->joinWith('disciplines')->where([
            'group_discipline.group_id' => $this->id,
            'group_discipline.discipline_id' => $dId
        ])->one();
    }
}