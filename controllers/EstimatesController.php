<?php


namespace app\controllers;

use app\forms\AddEstimateForm;
use app\models\Discipline;
use app\models\Estimate;
use app\models\Group;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class EstimatesController extends Controller
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
                        'roles' => ['viewTeacherCategories']
                    ],
                ]
            ]
        ];
    }


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
            'query' => User::getStudents($gId, true)
        ]);
        $data = User::getStudents($gId);

        if (!$group) {
            throw new NotFoundHttpException('Группы с идентификатором ' . $gId . ' не существует.');
        }

        if (!\Yii::$app->user->identity->hasRelationWithDiscipline($discipline->id) || !$group->hasDiscipline($discipline->id)) {
            throw new ForbiddenHttpException('У вас нет доступа к данному журналу');
        }

        return $this->render('view', [
            'group' => $group,
            'discipline' => $discipline,
            'dataProvider' => $dataProvider,
            'model' => new AddEstimateForm(),
            'data' => $data
        ]);
    }

    public function actionAddEstimate()
    {
        $model = new AddEstimateForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            Estimate::add($model);
            \Yii::$app->session->setFlash('success', 'Оценка выставлена.');
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

}