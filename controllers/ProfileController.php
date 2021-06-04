<?php


namespace app\controllers;

use app\models\Profile;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    public function actionIndex($username=null)
    {
        $user = User::findOne(['username' => $username ? $username : Yii::$app->user->identity->username]);
        if($user === null){
            throw new NotFoundHttpException('Пользователя '. $username .' не существует');
        }
        return $this->render('index', [
            'user' => $user
        ]);
    }
}