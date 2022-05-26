<?php

namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\forms\LoginForm;
use yii\web\ForbiddenHttpException;

class AuthController extends Controller
{
    public $layout = 'auth';

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);

        if ($action->id === 'login') {
            if ($user = Yii::$app->user->identity) {
                if ($user->getRole() === 'student') {
                    Yii::$app->user->logout();
                    throw new ForbiddenHttpException('У вас нет доступа к этой системе.');
                }
            }
        }

        return $result;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}