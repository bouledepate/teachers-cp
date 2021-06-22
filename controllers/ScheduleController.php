<?php


namespace app\controllers;


use app\helpers\ScheduleHelper;
use app\models\Discipline;
use app\models\Group;
use app\models\Schedule;
use app\forms\ScheduleForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ScheduleController extends \yii\web\Controller
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
                        'roles' => ['viewAdminCategories']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['viewStudentCategories', 'viewAdminCategories']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Group::find()
        ]);

        return $this->render('index-admin', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id, $week){
        $group = Group::findOne(['id' => $id]);

        if(!$group){
            throw new NotFoundHttpException('Расписание не может быть отображено: Группы не существует.');
        }

        if(\Yii::$app->user->identity->role === 'student'){
            if($group->id !== \Yii::$app->user->identity->group_id){
                throw new ForbiddenHttpException('Вы не можете просматривать расписание не своей группы.');
            }
        }

        $data = Schedule::find()->where(['group_id' => $id, 'week' => $week])->orderBy('time')->all();

        return $this->render('view', [
            'group' => $group,
            'data' => $data,
        ]);
    }

    public function actionGetDisciplines($id, $week){
        if(\Yii::$app->request->post('day')){
            $day = \Yii::$app->request->post('day');
        } else {
            $day = Schedule::DAY_MONDAY;
        }

        $query = Schedule::find()->where(['group_id' => $id, 'week' => $week, 'day' => (int)$day])->orderBy('time');
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return ScheduleHelper::displayScheduleByDay($dataProvider);;
    }

    public function actionEdit($id, $week){
        $model = new ScheduleForm();
        $disciplines = Discipline::getDisciplines($id);
        $group = Group::findOne(['id' => $id]);

        if(!$group){
            \Yii::$app->session->setFlash('danger', 'Выбранной группы не существует.');
            return $this->redirect(['schedule/index']);
        }

        $day = \yii::$app->request->post('day');

        return $this->render('edit', [
            'disciplines' => $disciplines,
            'model' => $model,
            'group' => $group,
            'day' => $day
        ]);
    }

    public function actionAdd(){
        $model = new ScheduleForm();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            Schedule::create($model);
            \Yii::$app->session->setFlash('success', 'Внесены корректировки в расписание группы.');
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionTransfer($id){
        $schedule = Schedule::findOne(['id' => $id]);

        if($schedule->load(\Yii::$app->request->post()) && $schedule->validate()){
            $schedule->save(false);
            \Yii::$app->session->setFlash('success', 'Дисциплина успешно перенесена на другое время.');
            return $this->redirect(['schedule/edit', 'id' => $schedule->group_id, 'week' => $schedule->week]);
        }

        return $this->render('transfer', ['schedule' => $schedule]);
    }

    public function actionRemove($id){
        $schedule = Schedule::findOne(['id' => $id]);
        $schedule->delete();

        \Yii::$app->session->setFlash('success', 'Дисциплина успешно убрана из расписания.');

        return $this->redirect(\Yii::$app->request->referrer);
    }
}