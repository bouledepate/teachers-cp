<?php

/* @var $user yii\models\User */

/* @var $profile yii\models\Profile */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = $user->username;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Информация о пользователе <?= $user->username ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="/control-panel/users/block/<?= $user->id ?>" class="btn btn-sm btn-outline-danger">
                <?= $user->status ? '<i class="bi bi-shield-fill-x"></i> Заблокировать' :
                    '<i class="bi bi-shield-slash-fill"></i> Разблокировать' ?>
            </a>
            <a href="/control-panel/users/update/<?= $user->id ?>" class="btn btn-sm btn-outline-success">
                <i class="bi bi-pencil-square"></i> Изменить
            </a>
        </div>
    </div>
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
            'value' => function ($data) {
                $url = Url::to(['users/change-password', 'id' => $data->id]);
                return $data->password . Html::a(' (изменить)', $url);
            },
            'format' => 'raw'
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
<h2>Профиль пользователя</h2>
<?= DetailView::widget([
    'model' => $profile,
    'options' => [
        'class' => 'table table-bordered table-striped'
    ],
    'attributes' => [
        [
            'label' => 'ID',
            'attribute' => 'id',
            'captionOptions' => ['width' => '25%'],
            'contentOptions' => ['width' => '75%'],
        ],
        [
            'label' => 'Имя',
            'attribute' => 'first_name'
        ],
        [
            'label' => 'Фамилия',
            'attribute' => 'last_name'
        ],
        [
            'label' => 'Группа',
            'attribute' => 'user.group.name'
        ],
        [
            'label' => 'Профиль',
            'value' => function ($data) {
                return Html::a('Ссылка', ['profile/index', 'username' => $data->user->username]);
            },
            'format' => 'raw'
        ],
    ]
])
?>
