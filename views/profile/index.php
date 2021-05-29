<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $profile yii\models\Profile */

$this->title = 'Ваш профиль';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Yii::$app->user->identity->username ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="/control-panel/users/create" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-pencil-square"></i> Изменить
            </a>
        </div>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-5">
            <p class="alert alert-success">Ваш статус в системе: <strong><?= \Yii::$app->user->identity->getRole() ?></strong></p>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $profile,
        'options' => [
                'class' => 'table table-sm table-bordered table-striped',
        ],
        'attributes' => [
            [
                    'label' => 'Имя',
                'attribute' => 'first_name',
                'captionOptions' => ['width' => '20%'],
                'contentOptions' => ['width' => '80%'],
            ],
            [
                    'label' => 'Фамилия',
                'attribute' => 'last_name'
            ],
            [
                    'label' => 'Электронная почта',
                'value' => $profile->user->email,
            ]
        ],
    ])
    ?>
</div>

