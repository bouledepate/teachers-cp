<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */
$this->title = "Успеваемость"; ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>

<?= \app\widgets\Alert::widget(); ?>

<div class="container">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-bordered table-sm'
        ],
        'columns' => [
            [
                'headerOptions' => ['width' => '250'],
                'label' => 'Дисциплина',
                'attribute' => 'name'
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\helpers\EstimateHelper::setCollapse($data->id, \Yii::$app->user->getId());
                }
            ],
            [
                'headerOptions' => ['width' => '100'],
                'label' => 'Баллы',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\helpers\EstimateHelper::setButton($data->id);
                }
            ],
            [
                'headerOptions' => ['width' => '50'],
                'label' => 'Итог',
                'format' => 'raw',
                'value' => function ($data) {
                    return \app\helpers\EstimateHelper::totalEstimateDisplay(\Yii::$app->user->getId(), $data->id);
                }
            ]
        ]
    ]) ?>
</div>
