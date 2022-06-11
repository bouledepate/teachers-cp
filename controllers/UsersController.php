<?php


namespace app\controllers;

use app\forms\UploadImageForm;
use app\helpers\UserHelper;
use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;

use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

use app\forms\SignupForm;
use app\forms\ChangePasswordForm;

use app\models\User;
use app\models\Profile;
use app\models\search\UserSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class UsersController extends Controller
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
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionView($id)
    {
        $user = User::findOne(['id' => $id]);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return $this->render('view', [
            'user' => $user,
            'profile' => $user->profile
        ]);

    }

    public function actionCreate()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::create($model);
            if ($user) $model->createProfile($user->id);
            \Yii::$app->session->setFlash('success', 'Пользователь создан. Пароль: ' . $model->password);
            return $this->redirect('/users/index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionBlock($id)
    {
        $user = User::findOne(['id' => $id]);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        if (\Yii::$app->user->getId() != $user->id) {
            $user->changeStatus();
            \Yii::$app->session->setFlash('info', UserHelper::getBlockMessageToFlash($user));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdate($id)
    {
        $user = User::findOne($id);
        $profile = $user->profile;
        $items = [
            'admin' => 'Администратор',
            'teacher' => 'Преподаватель',
            'student' => 'Студент'
        ];
        $params = [
            'prompt' => 'Выберите роль...',
        ];

        if (!isset($user, $profile)) {
            throw new NotFoundHttpException('Пользователь не найден ');
        }

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            $isValid = $user->validate();
            $isValid = $profile->validate() && $isValid;

            if ($isValid) {
                $user->setRole(Yii::$app->request->post()['User']['role']);
                $user->save(false);
                $profile->save(false);

                \Yii::$app->session->setFlash('success', 'Данные пользователя успешно обновлены.');

                return $this->redirect(['/users/view', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'user' => $user,
            'profile' => $profile,
            'items' => $items,
            'params' => $params,
        ]);
    }

    public function actionChangePassword($id)
    {
        $model = new ChangePasswordForm();
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $user->setPassword($model->password);
            $user->save();

            \Yii::$app->session->setFlash('success', 'Пароль изменён: ' . $model->password);

            return $this->redirect(['/users/view', 'id' => $id]);
        }

        return $this->render('change-password', [
            'model' => $model
        ]);
    }

    public function actionSetImage($id)
    {
        $model = new UploadImageForm();
        $profile = Profile::findOne(['id' => $id]);

        if (\Yii::$app->request->isPost) {

            $file = UploadedFile::getInstance($model, 'image');

            if ($profile->saveImage($model->uploadImage($file, $profile->avatar))) {
                return $this->redirect(['users/view', 'id' => $profile->user_id]);
            }
        }

        return $this->render('set-image', [
            'model' => $model,
            'profile' => $profile
        ]);
    }

    public function actionDelete(int $id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        $user->delete()
            ? Yii::$app->session->setFlash('success', 'Пользователь удалён.')
            : Yii::$app->session->setFlash('error', 'Ошибка при удалении пользователя');

        return $this->redirect(Yii::$app->request->referrer);
    }
}