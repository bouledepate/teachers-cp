<?php


namespace app\controllers;

use app\forms\AddDisciplineForm;
use app\models\Discipline;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Group;
use app\forms\CreateGroupForm;
use app\forms\AddStudentForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class GroupsController extends Controller
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
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Group::find()
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new CreateGroupForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            Group::create($model);
            return $this->redirect('/groups');
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        $group = Group::findOne(['id' => $id]);

        if ($group === null) {
            throw new NotFoundHttpException('Информация о группе в системе не найдена');
        }

        $studentDataProvider = new ActiveDataProvider([
            'query' => $group->getUsers()
        ]);
        $disciplineDataProvider = new ActiveDataProvider([
            'query' => $group->getDisciplines()
        ]);

        $studentData = User::getStudentsByGroup($id);
        $disciplineData = Discipline::getDisciplinesByGroup($id);

        return $this->render('view', [
            'group' => $group,
            'studentDataProvider' => $studentDataProvider,
            'studentData' => ArrayHelper::map($studentData, 'id', 'full_name'),
            'studentModel' => new AddStudentForm(),
            'disciplineDataProvider' => $disciplineDataProvider,
            'disciplineData' => ArrayHelper::map($disciplineData, 'id', 'name'),
            'disciplineModel' => new AddDisciplineForm()
        ]);
    }

    public function actionUpdate($id)
    {
        $group = Group::findOne($id);

        if ($group === null) {
            throw new NotFoundHttpException("Данная группа не найдена");
        }

        if ($group->load(\Yii::$app->request->post()) && $group->validate()) {
            $group->save(false);

            \Yii::$app->session->setFlash('success', 'Данные группы изменены.');

            return $this->redirect(['groups/view', 'id' => $id]);
        }

        return $this->render('update', [
            'group' => $group
        ]);
    }

    public function actionAddStudent($id)
    {
        $model = new AddStudentForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            Group::addStudents(\Yii::$app->request->post()['AddStudentForm']['studentId'], $id);
            \Yii::$app->session->setFlash('success', 'Студенты зачислены в группу.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemoveStudent($id)
    {
        Group::removeStudent($id);
        \Yii::$app->session->setFlash('success', 'Студен был исключён из группы.');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAddDiscipline($id)
    {
        $model = new AddDisciplineForm();
        $group = Group::findOne(['id' => $id]);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $group->addDisciplines(\Yii::$app->request->post()['AddDisciplineForm']['disciplineId']);
            \Yii::$app->session->setFlash('success', 'Дисциплины были успешно закреплены за группой.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemoveDiscipline($id, $disciplineId)
    {
        $group = Group::findOne(['id' => $id]);
        $group->removeDiscipline($disciplineId);
        \Yii::$app->session->setFlash('success', 'Дисциплина успешно откреплены от группы.');
        return $this->redirect(\Yii::$app->request->referrer);
    }
}