<?php


namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\forms\SignupForm;
use app\models\User;
use yii\base\BaseObject;
use yii\web\ForbiddenHttpException;

class UsersController extends Controller
{
    public $layout = 'control-panel';

    public function actionIndex()
    {
        if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
            $dataProvider = new ActiveDataProvider(
                ['query' => User::find()]
            );
            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new ForbiddenHttpException("У вас нет доступа к данной странице.");
        }
    }

    public function actionView($id){
        if(Yii::$app->user->can('viewUser')){
            $user = User::findOne(['id' => $id]);
            return $this->render('view', [
                'user' => $user
            ]);
        } else {
            throw new ForbiddenHttpException("У вас нет доступа к данной странице");
        }
    }

    public function actionCreate(){
        $model = new SignupForm();
        $auth = Yii::$app->authManager;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::create($model);
            $auth->assign($auth->getRole('student'), $user->id);
            return $this->redirect('/users/index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionBlock($id){
        $user = User::findOne(['id' => $id]);
        if(\Yii::$app->user->getId()!=$user->id){
            $user->changeStatus();
        }
        return $this->redirect('/users/index');
    }

    public function actionUpdate($id){

    }
}