<?php

namespace app\controllers;

use app\models\Certification;
use app\models\Discipline;
use app\models\Group;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class CertificationController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['viewTeacherCategories']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Discipline::find()
                ->joinWith('users')
                ->where(['user.id' => \Yii::$app->user->getId()])

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCheckCertification(int $group, int $discipline): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Certification::find()->where(['group_id' => $group, 'discipline_id' => $discipline]),
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC]
            ]
        ]);

        $additionalData = $this->getCertificationAdditionalData($group, $discipline);

        return $this->render('check-certification', array_merge(['dataProvider' => $dataProvider], $additionalData));
    }

    public function actionFillCertification(int $group, int $discipline): string
    {
        $model = new Certification();

        $additionalData = $this->getCertificationAdditionalData($group, $discipline);

        return $this->render('fill-certification', array_merge(['model' => $model], $additionalData));
    }

    protected function getCertificationAdditionalData(int $group, int $discipline): array
    {
        $group = Group::findOne(['id' => $group]);
        $discipline = Discipline::findOne(['id' => $discipline]);

        return [
            'group' => $group ?? null,
            'discipline' => $discipline ?? null
        ];
    }
}