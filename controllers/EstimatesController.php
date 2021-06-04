<?php


namespace app\controllers;

use app\models\Discipline;
use app\models\Group;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;


class EstimatesController extends Controller
{
    public function actionIndex()
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

    public function actionView($gId, $dId)
    {
        $group = Group::findOne(['id' => $gId]);
        $discipline = Discipline::findOne(['id' => $dId]);
        $dataProvider = new ActiveDataProvider([
            'query' => User::getGroupStudentsToDisplay($group->id)
        ]);
        return $this->render('view', [
            'group' => $group,
            'discipline' => $discipline,
            'dataProvider' => $dataProvider
        ]);
    }
}