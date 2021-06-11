<?php


namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * @property int $id
 * @property int $user_discipline_id
 * @property int $author_id
 * @property date $created_at
 * @property int $value
 */
class Estimate extends ActiveRecord
{
    public function rules()
    {
        return [
            'required' => [['author_id', 'user_discipline_id'], 'required'],
            'integer' => [['id', 'author_id', 'user_discipline_id'], 'integer'],
            'date' => ['created_at', 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_discipline_id' => 'ID связи user-discipline',
            'author_id' => 'ID преподавателя',
            'created_at' => 'Дата выставления',
            'value' => 'Оценка'
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public static function add($model, $returned = false)
    {
        $userDiscipline = User::getUserDisciplineRelationId($model->userId, $model->disciplineId);

        $estimate = new Estimate();
        $estimate->user_discipline_id = $userDiscipline->id;
        $estimate->author_id = $model->authorId;
        $estimate->created_at = $model->createdAt;
        $estimate->value = $model->value;
        $estimate->save();

        if ($returned) {
            return $estimate;
        }
    }
}