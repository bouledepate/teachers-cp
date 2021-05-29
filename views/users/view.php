<?php

/* @var $user yii\models\User */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $user->username;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Информация о пользователе <?= $user->username ?></h1>
</div>
<?= DetailView::widget([
    'model' => $user,
    'attributes' => [
        [
            'label' => 'ID',
            'attribute' => 'id'
        ],
        [
            'label' => 'Тип пользователя',
            'value' => $user->getRole()
        ],
        [
            'label' => 'Статус аккаунта',
            'value' => $user->status ? 'Активен' : 'Заблокирован',
            'contentOptions' => [
                    'class' => $user->status ? 'text-success' : 'text-danger'
            ]
        ],
        [
            'label' => 'Имя пользователя',
            'attribute' => 'username'
        ],
        [
            'label' => 'Электронная почта',
            'attribute' => 'email',
        ],
        [
            'label' => 'Пароль',
            'attribute' => 'password',
            'visible' => (bool)Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->getId()),
        ],
        [
            'label' => 'Ключ авторизации',
            'attribute' => 'auth_key',
            'visible' => (bool)Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->getId()),
        ],
        [
            'label' => 'Токен доступа',
            'attribute' => 'access_token',
            'visible' => (bool)Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->getId()),
        ],
    ],
])
?>
