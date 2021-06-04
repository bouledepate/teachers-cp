<?php

/**
 * @var $discipline app\models\Discipline
 */

use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap4\ActiveForm;

$this->title = $discipline->name;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Дисциплина: <?= $discipline->name ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['disciplines/update', 'id' => $discipline->id]) ?>" class="btn btn-sm btn-outline-success">
                <i class="bi bi-pencil-square"></i> Изменить
            </a>
        </div>
    </div>
</div>
<div class="container">
    <?= DetailView::widget([
        'model' => $discipline,
        'options' => [
            'class' => 'table table-bordered table-striped'
        ],
        'attributes' => [
            [
                'label' => 'ID',
                'attribute' => 'id',
                'captionOptions' => ['width' => '25%'],
                'contentOptions' => ['width' => '75%'],
            ],
            [
                'label' => 'Название дисциплины',
                'attribute' => 'name'
            ],
        ]
    ]) ?>
    <hr class="my-2">
    <div class="row">
        <div class="col-8">
            <h5>Закреплённые преподаватели</h5>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered table-sm'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Имя аккаунта',
                        'attribute' => 'username'
                    ],
                    [
                        'label' => 'ФИО',
                        'value' => function ($data) {
                            return $data->profile->getFullName();
                        }
                    ],
                    [
                        'label' => 'Электронная почта',
                        'attribute' => 'email'
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Действия',
                        'headerOptions' => ['width' => '80'],
                        'template' => '<center>{view} {remove}</center>',
                        'buttons' => [
                            'remove' => function ($url, $model, $key)
                            {
                                return Html::a('<i class="bi bi-person-dash-fill"></i>', $url);
                            }
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) use ($discipline) {
                            if($action === 'view'){
                                return Url::to(['users/view', 'id' => $model->id]);
                            }
                            if($action === 'remove'){
                                return Url::to(['disciplines/remove-teacher', 'id' => $discipline->id, 'userId'=> $model->id]);
                            }
                        }
                    ],
                ]
            ]) ?>
        </div>
        <div class="col-4">
            <h5>Прикрепить преподавателя</h5>
            <?php $form = ActiveForm::begin([
                'layout' => 'default',
                'action' => Url::to(['disciplines/add-teacher', 'id'=>$discipline->id]),
                'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
            ]) ?>
            <?= $form->field($model, 'teacherId')->widget(Select2::className(), [
                'bsVersion' => 4,
                'data' => $data,
                'options' => [
                    'placeholder' => 'Выберите пользователя',
                    'multiple' => true,
                    'autocomplete' => 'off'
                ],
                'theme' => Select2::THEME_DEFAULT,
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
            <?= Html::submitButton("Прикрепить", [
                'class' => 'btn btn-primary'
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

