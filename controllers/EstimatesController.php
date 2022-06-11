<?php


namespace app\controllers;

use app\enums\MonthEnum;
use app\forms\AddEstimateForm;
use app\helpers\EstimateHelper;
use app\models\Discipline;
use app\models\Estimate;
use app\models\Group;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
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
        // Список студентов для виджета выставления оценок в формате [ID -> Фамилия Имя]
        $data = User::getStudents($gId);

        $groupData = EstimateHelper::getMarksTableData($gId, $dId);

        \Yii::$app->session->set('marksData', $groupData['result']);
        \Yii::$app->session->set('includedMonths', $groupData['includedMonths']);

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
            \Yii::$app->session->setFlash('success', 'Баллы выставлены.');
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionRemoveMark($id)
    {
        $model = Estimate::findOne(['id' => $id]);

        if ($model) {
            if ($model->delete()) {
                \Yii::$app->session->setFlash('success', 'Баллы удалены');
            } else {
                \Yii::$app->session->setFlash('error', 'Ошибка при удалении баллов');
            }
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionRemoveMarks($id)
    {
        if (Estimate::removeAllMarks($id)) {
            \Yii::$app->session->setFlash('success', 'Все баллы удалены');
        } else {
            \Yii::$app->session->setFlash('error', 'Ошибка при удалении баллов');
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionRemoveMarksByMonth($id, $month)
    {
        if (Estimate::removeByMonth($id, $month)) {
            \Yii::$app->session->setFlash('success', 'Все баллы удалены');
        } else {
            \Yii::$app->session->setFlash('error', 'Ошибка при удалении баллов');
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionRemoveGroupMarks($id, $discipline)
    {
        Estimate::removeGroupMarks($id, $discipline);
        \Yii::$app->session->setFlash('success', 'Баллы всей группы были удалены');

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionGetData($group, $discipline, $month = null)
    {
         if (\Yii::$app->request->isAjax) {
             return Json::encode(EstimateHelper::getMarksTableData($group, $discipline, MonthEnum::getMonth($month)));
         }

         return $this->redirect(\Yii::$app->request->referrer);
    }
}