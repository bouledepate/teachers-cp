<?php


namespace app\controllers;

use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Discipline;
use app\models\User;
use app\forms\CreateDisciplineForm;
use yii\web\NotFoundHttpException;


class DisciplinesController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Discipline::find()
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = new CreateDisciplineForm();
        $discipline = Discipline::findOne(['id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => User::getTeachersToDisplay($id)
        ]);
        $data = User::getTeachersByDiscipline($id);

        if($discipline === null){
            throw new NotFoundHttpException('Предмет в базе не найден');
        }

        return $this->render('view', [
            'model' => $model,
            'discipline' => $discipline,
            'dataProvider' => $dataProvider,
            'data' => ArrayHelper::map($data, 'id', 'full_name')
        ]);
    }

    public function actionCreate()
    {
        $model = new CreateDisciplineForm();
        $data = User::getTeachers();
        $data = ArrayHelper::map($data, 'id', 'full_name');

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $discipline = Discipline::create($model, true);
            $discipline->setTeachers(\Yii::$app->request->post()['CreateDisciplineForm']['teacherId']);
            return $this->redirect('/disciplines/index');
        }

        return $this->render('create', [
            'model' => $model,
            'data' => $data
        ]);
    }

    public function actionUpdate($id)
    {
        $discipline = Discipline::findOne(['id' => $id]);

        if($discipline === null){
            throw new NotFoundHttpException('Предмет в базе не найден');
        }

        if($discipline->load(\Yii::$app->request->post()) && $discipline->validate()){
            $discipline->save(false);
            return $this->redirect('/disciplines/index');
        }

        return $this->render('update', [
            'discipline' => $discipline
        ]);
    }

    public function actionRemoveTeacher($id, $userId)
    {
        $discipline = Discipline::findOne(['id' => $id]);
        $discipline->removeTeacher($userId);

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionAddTeacher($id)
    {
        $discipline = Discipline::findOne(['id' => $id]);

        if($discipline === null){
            throw new NotFoundHttpException("Дисциплина не найдена");
        }

        if(\Yii::$app->request->post()){
            $discipline->setTeachers(\Yii::$app->request->post()['CreateDisciplineForm']['teacherId']);
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

}