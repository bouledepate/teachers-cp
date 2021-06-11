<?php


namespace app\controllers;

use app\models\Discipline;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;


class DiaryController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['viewStudentCategories']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Discipline::find()->joinWith('users')->where(['user.id' => \Yii::$app->user->getId()])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
}