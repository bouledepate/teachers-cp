<?php

use app\models\Certification;
use app\widgets\Alert;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\Group $group
 * @var \app\models\Discipline $discipline
 */


$this->title = "Аттестация группы " . $group->name; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1><br>
    <p class="lead"><?= $discipline->name ?></p>
</div>

<div class="container-fluid">

    <div class="btn-toolbar mb-3 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['certification/fill-certification', 'group' => $group->id, 'discipline' => $discipline->id]) ?>"
               class="btn btn-sm btn-outline-success"><i class="bi bi-person-plus-fill"></i>
                Заполнить данные аттестации</a>
        </div>
    </div>

    <?= Alert::widget(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-sm mt-3'
        ],
        'columns' => [
            [
                'label' => 'ID',
                'attribute' => 'id'
            ],
            [
                'label' => 'Тип аттестации',
                'attribute' => 'type',
                'value' => function ($data) {
                    return Certification::getCertificationTypes()[$data->type];
                }
            ],
            [
                'label' => 'Форма экзамена',
                'attribute' => 'subtype',
                'format' => 'raw',
                'value' => function ($data) {
                    if (isset($data->subtype)) {
                        return Certification::getExamTypes()[$data->subtype];
                    }

                    return '<i>(Не требуется)</i>';
                }
            ],
            [
                'label' => 'Период',
                'attribute' => 'period',
                'value' => function ($data) {
                    return implode(', ', \app\helpers\CertificationHelper::getMonthsByKeys($data->period));
                }
            ],
            [
                'label' => 'Дата проведения',
                'attribute' => 'date',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDatetime($data->date, 'php:d M yy г. H:i');
                }
            ]
        ],
    ]); ?>
</div>
