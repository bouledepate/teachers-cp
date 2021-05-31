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
        $group = Group::findOne(['id'=>$id]);
        $group->name = $params->name;
        $group->save();
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
}