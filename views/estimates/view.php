<?php

/**
 * @var $group app\models\Group
 * @var $discipline app\models\Discipline
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use yii\grid\GridView;

$this->title = 'Журнал группы '.$group->name ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Журнал группы <?= $group->name ?></h1>
    <p class="lead">Дисциплина: <?= $discipline->name ?></p>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-striped table-bordered table-sm'
    ],
    'columns' =>
        [
            [
                'label' => 'ФИО студента',
                'value' => function ($data) {
                    return $data->profile->getFullName();
                }
            ],
        ]
]) ?>
