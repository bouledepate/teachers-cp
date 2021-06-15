<?php

/**
 * @var $group app\models\Group
 * @var $discipline app\models\Discipline
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model app\forms\AddEstimateForm
 */

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Журнал группы ' . $group->name ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Журнал группы <?= $group->name ?></h1>
    <p class="lead"><?= $discipline->name ?></p>
</div>
<div class="container">
    <?php Pjax::begin(); ?>
    <div class="row">
        <div class="col-8">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-bordered table-sm'
                ],
                'columns' =>
                    [
                        [
                            'headerOptions' => ['width' => '150'],
                            'label' => 'ФИО студента',
                            'value' => function ($data) {
                                return $data->profile->getFullName();
                            },
                        ],
                        [
                            'label' => '',
                            'format' => 'raw',
                            'value' => function ($data) use ($discipline) {
                                return \app\helpers\EstimateHelper::setCollapse($discipline->id, $data->id, false, true);
                            }
                        ],
                        [
                            'headerOptions' => ['width' => '100'],
                            'label' => 'Баллы',
                            'format' => 'raw',
                            'value' => function ($data) use ($discipline) {
                                return \app\helpers\EstimateHelper::setButton($data->id);
                            }
                        ],
                        [
                            'headerOptions' => ['width' => '50'],
                            'label' => 'Итог',
                            'format' => 'raw',
                            'value' => function ($data) use ($discipline) {
                                return \app\helpers\EstimateHelper::totalEstimateDisplay($data, $discipline);
                            }
                        ]
                    ]
            ]) ?>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <?= \app\widgets\Alert::widget() ?>
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'action' => Url::to(['estimates/add-estimate']),
                    'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
                ]) ?>

                <?= $form->field($model, 'userId')->widget(Select2::className(), [
                    'bsVersion' => 4,
                    'data' => $data,
                    'options' => ['placeholder' => 'Выберите студента',
                        'autocomplete' => 'off'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => ['allowClear' => true]
                ])->hint('Выберите студента, которому нужно выставить оценку.') ?>
                <?= $form->field($model, 'value')
                    ->textInput(['type' => 'number', 'min' => '0', 'max' => '100', 'placeholder' => 'Кол-во баллов'])
                    ->hint('Количество баллов не должно превышать 100, как в данном случае, так и в общем за всё время.') ?>
                <?= $form->field($model, 'createdAt')->textInput(['type' => 'date']) ?>
                <?= $form->field($model, 'authorId')->hiddenInput(['value' => \Yii::$app->user->getId()]) ?>
                <?= $form->field($model, 'disciplineId')->hiddenInput(['value' => $discipline->id]) ?>
                <?= Html::submitButton('Выставить', ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>


