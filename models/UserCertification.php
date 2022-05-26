<?php

namespace app\models;

use Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $certification_id
 * @property int $user_id
 * @property int $mark
 * @property int $ticket
 * @property-read Certification $certification
 * @property-read User $user
 */
class UserCertification extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['certification_id', 'user_id', 'mark'], 'required'],
            [['certification_id', 'user_id', 'mark', 'ticket'], 'integer'],
            ['certification_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Certification::class],
            ['user_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::class],
            ['ticket', 'ticketValidate']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'certification_id' => 'Аттестация',
            'user_id' => 'Студент',
            'mark' => 'Баллы',
            'ticket' => 'Номер билета'
        ];
    }

    public function getCertification(): ActiveQuery
    {
        return $this->hasOne(Certification::class, ['id' => 'certification_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function saveMany(array $data, int $certificationId)
    {
        $data = self::groupSaveData($data, $certificationId);

        self::getDb()->transaction(function ($db) use ($data) {
            \Yii::$app->db->createCommand()->batchInsert('user_certification', ['certification_id', 'user_id', 'mark', 'ticket'], $data)->execute();
        });
    }

    public function ticketValidate($attribute, $params): void
    {
        if ($this->certification->type === Certification::TYPE_EXAM) {
            if (empty($this->$attribute)) {
                $this->addError($attribute, 'Необходимо указать номер билета');
            }
        }
    }

    protected static function groupSaveData(array $data, int $certificationId): array
    {
        $result = [];

        for ($id = 0; $id < $data['count']; $id++) {
            $result[] = [
                $certificationId,
                $data['users'][$id],
                $data['marks'][$id],
                $data['tickets'][$id] ?? null
            ];
        }

        return $result;
    }
}