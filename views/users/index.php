<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\search\UserSearch $searchModel
 */
$this->title = "Пользователи";
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="/control-panel/users/create" class="btn btn-sm btn-outline-success"><i
                        class="bi bi-person-plus-fill"></i> Создать
            </a>
        </div>
    </div>
</div>
<div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-sm'
        ],
        'columns' => [
            [
                'label' => 'ID',
                'attribute' => 'id'
            ],
            [
                'label' => 'Имя аккаунта',
                'attribute' => 'username',
            ],
            [
                'label' => 'Электронная почта',
                'attribute' => 'email'
            ],
            [
                'label' => 'Статус аккаунта',
                'attribute' => 'status',
                'filter' => \app\helpers\UserHelper::statusList(),
                'value' => function(\app\models\User $model){
                    return app\helpers\UserHelper::statusLabel($model->status);
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {block}{link}',
                'buttons' => [
                    'block' => function ($url, $model, $key) {
                        if($model->status === 1) {
                            return Html::a('<i class="bi bi-shield-fill-x"></i>', $url);
                        } else {
                            return Html::a('<i class="bi bi-shield-slash-fill"></i>', $url);
                        }
                    }
                ],
            ],
        ],
    ]); ?>
</div>
