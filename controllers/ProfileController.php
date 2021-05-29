<?php


namespace app\controllers;

use app\models\Profile;
use Yii;
use yii\web\Controller;

class ProfileController extends Controller
{
    public $layout = 'control-panel';

    public function actionIndex()
    {
        $profile = Profile::findOne(['id' => Yii::$app->user->id]);
        return $this->render('index', [
            'profile' => $profile
        ]);
    }
}