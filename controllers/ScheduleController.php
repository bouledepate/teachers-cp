<?php


namespace app\controllers;


use app\models\Discipline;
use app\models\Group;
use app\models\Schedule;
use app\forms\ScheduleForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

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
                        'actions' => ['edit', 'add', 'transfer', 'remove', 'index'],
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
        if(!$id){
            \Yii::$app->session->setFlash('warning', 'Не указана группа для отображения расписания.');
            return $this->redirect(\Yii::$app->request->referrer);
        }

        $group = Group::findOne(['id' => $id]);

        if(!$group){
            \Yii::$app->session->setFlash('danger', 'Выбранной группы не существует.');
            return $this->redirect(['schedule/index']);
        }

        $data = Schedule::find()->where(['group_id' => $id, 'week' => $week])->orderBy('time')->all();

        return $this->render('view', [
            'group' => $group,
            'data' => $data,
        ]);
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
        if(!$day){
            $day = Schedule::DAY_MONDAY;
        }

        $data = Schedule::find()->where(['group_id' => $id, 'week' => $week, 'day' => $day])->orderBy('time');
        $dataProvider = new ActiveDataProvider([
            'query' => $data
        ]);

        return $this->render('edit', [
            'disciplines' => $disciplines,
            'data' => $data->all(),
            'model' => $model,
            'group' => $group,
            'dataProvider' => $dataProvider,
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