<?php


namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;

use yii\web\Controller;
use yii\web\ForbiddenHttpException;

use app\forms\SignupForm;
use app\forms\ChangePasswordForm;

use app\models\User;
use app\models\Profile;


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
            $profile = $user->profile;
            return $this->render('view', [
                'user' => $user,
                'profile' => $profile
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
            Profile::create($user->id);
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
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdate($id){
        $user = User::findOne($id);
        $profile = Profile::findOne($id);
        $items = [
            1 => 'Администратор',
            2 => 'Преподаватель',
            3 => 'Студент'
        ];
        $params = [
            'prompt' => 'Выберите роль...',
        ];

        if (!isset($user, $profile)) {
            throw new NotFoundHttpException("Пользователь не найден");
        }

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            $isValid = $user->validate();
            $isValid = $profile->validate() && $isValid;
            if ($isValid) {
                $user->setRole(Yii::$app->request->post()['User']['role']);
                $user->save(false);
                $profile->save(false);

                return $this->redirect(['/users/view', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'user' => $user,
            'profile' => $profile,
            'items' => $items,
            'params' => $params
        ]);
    }

    public function actionChangePassword($id)
    {
        $model = new ChangePasswordForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $user = User::findOne($id);
            $user->setPassword($model->password);
            $user->save();
            return $this->redirect(['/users/view', 'id' => $id]);
        }
        return $this->render('change-password', [
            'model' => $model
        ]);
    }
}