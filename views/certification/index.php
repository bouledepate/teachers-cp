<?php

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Аттестация'; ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>

<?= \app\widgets\Alert::widget(); ?>

<div class="">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_discipline',
        'viewParams' => [
                'grop'
        ],
        'emptyText' => 'Дисциплины не найдены',
        'summary' => 'Показаны дисциплины <strong>{begin}-{end}</strong> из <strong>{totalCount}</strong>.'
    ]); ?>
</div>

