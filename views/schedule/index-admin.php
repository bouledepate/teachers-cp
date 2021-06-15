<?php

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use app\helpers\ScheduleHelper;
use app\models\Schedule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Расписание';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<?= \app\widgets\Alert::widget(); ?>
<div class="container">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-sm table-bordered table-striped'
        ],
        'columns' => [
            [
                'label' => 'Группа',
                'attribute' => 'name'
            ],
            [
                'label' => 'Расписание: числитель',
                'value' => function ($data) {
                    return ScheduleHelper::checkScheduleByWeekType(Schedule::WEEK_NUM, $data->id);
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'template' => '{view} {update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['schedule/view', 'id' => $model->id, 'week' => Schedule::WEEK_NUM]);
                    } else {
                        return Url::to(['schedule/edit', 'id' => $model->id, 'week' => Schedule::WEEK_NUM]);
                    }
                }
            ],
            [
                'label' => 'Расписание: знаменатель',
                'value' => function ($data) {
                    return ScheduleHelper::checkScheduleByWeekType(Schedule::WEEK_DENOM, $data->id);
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'template' => '{view} {update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['schedule/view', 'id' => $model->id, 'week' => Schedule::WEEK_DENOM]);
                    } else {
                        return Url::to(['schedule/edit', 'id' => $model->id, 'week' => Schedule::WEEK_DENOM]);
                    }
                }
            ],
        ]
    ]); ?>
</div>