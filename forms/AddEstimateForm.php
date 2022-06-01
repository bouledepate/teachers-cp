<?php


namespace app\forms;


use app\models\Estimate;
use app\models\User;
use yii\db\Query;

class AddEstimateForm extends \yii\base\Model
{
    public $authorId;
    public $createdAt;
    public $value;
    public $userId;
    public $disciplineId;

    public function rules()
    {
        return [
            [['userId', 'disciplineId', 'authorId', 'value'], 'required'],
            [['authorId', 'disciplineId', 'userId', 'value'], 'integer'],
            ['createdAt', 'date', 'format' => 'php:Y-m-d'],
            ['createdAt', 'dateValidator']
        ];
    }

    public function attributeLabels()
    {
        return [
            'authorId' => 'Преподаватель',
            'userId' => 'Студент',
            'value' => 'Баллы',
            'disciplineId' => 'Дисцилпина',
            'createdAt' => 'Выставлено'
        ];
    }

    public function dateValidator(string $attribute, ?array $params)
    {
        $userDisciplineId = User::getUserDisciplineRelationId($this->userId, $this->disciplineId);

        $result = (new Query())->select('created_at')
            ->from('estimate')
            ->where(['user_discipline_id' => $userDisciplineId->id])
            ->all();

        array_map(function ($data) use ($attribute) {
            if ($data['created_at'] === $this->createdAt) {
                $this->addError($attribute, 'Вы не можете выставить оценку на эту дату.');
                \Yii::$app->session->setFlash('error', 'Вы не можете выставить оценку на эту дату.');
            }
        }, $result);
    }
}