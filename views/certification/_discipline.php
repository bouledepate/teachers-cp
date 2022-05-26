<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

?>

<div class="post mt-2">
    <h4><?= $model->name ?></h4>
    <?php foreach ($model->groups as $group): ?>
        <div class="group my-2">
            <?= DetailView::widget([
                'model' => $group,
                'options' => [
                    'class' => 'table table-sm table-bordered table-striped',
                ],
                'attributes' => [
                    [
                        'label' => 'Группа',
                        'attribute' => 'name',
                        'captionOptions' => ['width' => '20%'],
                        'contentOptions' => ['width' => '80%'],
                    ],
                    [
                        'label' => 'Аттестация группы',
                        'value' => function ($data) use ($model, $group) {
                            return Html::a('Подробнее', Url::to([
                                'certification/check-certification',
                                'group' => $group->id,
                                'discipline' => $model->id
                            ]));
                        },
                        'format' => 'raw'
                    ]
                ],
            ]); ?>
        </div>
    <?php endforeach; ?>
</div>