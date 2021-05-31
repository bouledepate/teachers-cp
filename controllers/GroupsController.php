<?php


namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;
use app\models\Group;
use app\forms\CreateGroupForm;
use app\forms\AddStudentForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class GroupsController extends Controller
{
    public $layout = 'control-panel';

    public function actionIndex()
    {
        if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->getId())) {
            $dataProvider = new ActiveDataProvider([
                'query' => Group::find()
            ]);
            return $this->render('index', [
                'dataProvider' => $dataProvider
            ]);
        }
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
        $dataProvider = new ActiveDataProvider([
            'query' => $group->getUsers()
        ]);
        return $this->render('view', [
            'group' => $group,
            'dataProvider' => $dataProvider,
            'data' => User::find()->select(['id as id', 'username as label', 'username as value'])->asArray()->all(),
            'model' => new AddStudentForm()
        ]);
    }

    public function actionUpdate($id)
    {
        $group = Group::findOne($id);
        if($group === null){
            throw new NotFoundHttpException("Данная группа не найдена");
        }
        if($group->load(\Yii::$app->request->post()) && $group->validate()){
            $group->save(false);
            return $this->redirect(['groups/view', 'id'=>$id]);
        }
        return $this->render('update', [
            'group' => $group
        ]);
    }

    public function actionAddStudent()
    {
        $model = new AddStudentForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $user = User::findByUsername($model->username);
            if ($user === null) {
                throw new NotFoundHttpException("Пользователь не найден");
            } else {
                $user->setGroup($model->groupId);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemoveStudent($id)
    {
        $user = User::findIdentity($id);
        if($user === null){
            throw new NotFoundHttpException("Пользователь не найден");
        }
        $user->setGroup(0);
        return $this->redirect(Yii::$app->request->referrer);
    }
}