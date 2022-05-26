<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $group_id
 * @property int $discipline_id
 * @property int $type
 * @property int $subtype
 * @property int $period
 * @property-read Group $group
 * @property-read Discipline $discipline
 * @property-read array $results
 */
class Certification extends \yii\db\ActiveRecord
{
    public const TYPE_EXAM = 0;
    public const TYPE_MIDTERM = 1;

    public const TYPE_ORAL_EXAM = 0;
    public const TYPE_WRITTEN_EXAM = 1;

    public function rules(): array
    {
        return [
            [['group_id', 'discipline_id', 'type', 'date'], 'required'],
            [['group_id', 'discipline_id', 'type', 'subtype', 'period'], 'integer'],
            ['group_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Group::class, 'message' => 'Указанной группы не существует'],
            ['discipline_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Discipline::class, 'message' => 'Указанной дисципилины не существует'],
            ['date', 'datetime', 'format' => 'php:d.m.Y hh:mm:ss'],
            ['type', 'certificationTypeValidate'],
            ['subtype', 'examTypeValidate']
        ];
    }

    public static function getCertificationTypes(): array
    {
        return [
            self::TYPE_EXAM => 'Экзамен',
            self::TYPE_MIDTERM => 'Зачёт'
        ];
    }

    public static function getExamTypes(): array
    {
        return [
            self::TYPE_ORAL_EXAM => 'Устный',
            self::TYPE_WRITTEN_EXAM => 'Письменный'
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'group_id' => 'Группа',
            'discipline_id' => 'Дисциплина',
            'type' => 'Тип аттестации',
            'subtype' => 'Форма аттестации',
            'period' => 'Период'
        ];
    }

    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    public function getDiscipline(): ActiveQuery
    {
        return $this->hasOne(Discipline::class, ['id' => 'discipline_id']);
    }

    public function getResults(): ActiveQuery
    {
        return $this->hasMany(UserCertification::class, ['certification_id' => 'id']);
    }

    public function certificationTypeValidate($attribute, $params): void
    {
        if (!in_array($this->$attribute, array_keys(self::getCertificationTypes()))) {
            $this->addError($attribute, 'Выбран неверный тип аттестации');
        }
    }

    public function examTypeValidate($attribute, $params): void
    {
        if ($this->type === self::TYPE_EXAM) {
            if (empty($this->$attribute)) {
                $this->addError($attribute, 'Не выбрана форма экзамена');
            }

            if (!in_array($this->$attribute, array_keys(self::getExamTypes()))) {
                $this->addError($attribute, 'Выбрана неверная форма экзамена');
            }
        }
    }
}