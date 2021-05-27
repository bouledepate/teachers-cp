<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

class ControlPanelController extends Controller
{
    public $layout = 'control-panel';
    public function actionIndex()
    {
        $totalUsers = User::find()->count();
        return $this->render('index', [
            'totalUsers' => $totalUsers,
        ]);
    }
}