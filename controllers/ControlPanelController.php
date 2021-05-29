<?php

namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\models\User;
use yii\web\ForbiddenHttpException;
use app\forms\SignUpForm;

class ControlPanelController extends Controller
{
    public $layout = 'control-panel';

    public function actionIndex()
    {
        return $this->render('index');
    }

}