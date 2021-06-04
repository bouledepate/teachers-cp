<?php


namespace app\models;

use yii\db\ActiveRecord;

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
            'required' => [['id', 'author_id', 'user_discipline_id'], 'required'],
            'integer' => [['id', 'author_id', 'user_discipline_id'], 'integer'],
            'date' => ['created_at', 'date']
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
        return $this->hasOne(User::class, ['author_id' => 'id']);
    }
}