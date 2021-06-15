<?php

use app\widgets\Alert;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \yii\data\ActiveDataProvider $dataProvider */
$this->title = "Группы"; ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['groups/create']) ?>" class="btn btn-sm btn-outline-success"><i
                        class="bi bi-person-plus-fill"></i> Создать
            </a>
        </div>
    </div>
</div>
<?= Alert::widget(); ?>
<div class="container">
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
                'label' => 'Название группы',
                'attribute' => 'name'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>
</div>
