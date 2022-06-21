<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */
$this->title = "Дисциплины"; ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['disciplines/create']) ?>" class="btn btn-sm btn-outline-success"><i
                        class="bi bi-person-plus-fill"></i> Создать
            </a>
        </div>
    </div>
</div>

<?= \app\widgets\Alert::widget(); ?>

<div class="">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-sm'
        ],
        'columns' => [
            [
                'label' => 'ID',
                'attribute' => 'id'
            ],
            [
                'label' => 'Наименование дисциплины',
                'attribute' => 'name'
            ],
            [
                'label' => 'Модуль',
                'attribute' => 'module'
            ],
            [
                'label' => 'Преподаватели',
                'format' => 'raw',
                'value' => function ($data) {
                    $items = [];
                    foreach ($data->users as $user) {
                        if($user->role === 'teacher'){
                            $items[] = Html::a($user->profile->fullName, ['users/view', 'id'=>$user->id]);
                        }
                    }
                    return $items ? implode(', ', $items) : null;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '<center>{view} {update} {delete}</center>',
            ],
        ]
    ]); ?>
</div>
