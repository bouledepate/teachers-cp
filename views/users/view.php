<?php

/* @var $user app\models\User */

/* @var $profile app\models\Profile */

use app\helpers\UserHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = $user->username; ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Информация о пользователе <?= $user->username ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['users/block', 'id' => $user->id]) ?>" class="btn btn-sm btn-outline-danger">
                <?= $user->status ? '<i class="fas fa-ban"></i> Заблокировать' :
                    '<i class="far fa-check-circle"></i> Разблокировать' ?>
            </a>
            <a href="<?= Url::to(['users/update', 'id' => $user->id]) ?>" class="btn btn-sm btn-outline-success">
                <i class="bi bi-pencil-square"></i> Изменить
            </a>
        </div>
    </div>
</div>
<?= \app\widgets\Alert::widget() ?>
<div class="container">
    <?= DetailView::widget([
        'model' => $user,
        'attributes' => [
            [
                'label' => 'ID',
                'attribute' => 'id',
                'captionOptions' => ['width' => '25%'],
                'contentOptions' => ['width' => '75%'],
            ],
            [
                'label' => 'Тип пользователя',
                'value' => function ($data) {
                    return UserHelper::roleName($data->role);
                }
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
                'visible' => (bool)Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->getId()),
                'value' => function ($data) {
                    $url = Url::to(['users/change-password', 'id' => $data->id]);
                    return Html::a(' (изменить)', $url);
                },
                'format' => 'raw'
            ]
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
                'label' => 'Фотография',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data->getImage(), ['width' => 150, 'height'=>150]);
                }
            ],
            [
                'label' => '',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a('Изменить фотографию',
                        ['users/set-image', 'id' => $data->id],
                        ['class' => 'btn btn-sm btn-primary']);
                }
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
</div>