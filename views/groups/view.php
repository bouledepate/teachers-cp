<?php

/**
 * @var $group yii\models\Group
 * @var $users yii\models\User
 */

use app\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\bootstrap4\Modal;
use yii\bootstrap4\ActiveForm;
use yii\jui\AutoComplete;

$this->title = 'Группа ' . $group->name;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Информация о группе <?= $group->name ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="/control-panel/groups/update/<?= $group->id ?>" class="btn btn-sm btn-outline-success">
                <i class="bi bi-pencil-square"></i> Изменить
            </a>
        </div>
    </div>
</div>
<?= DetailView::widget([
    'model' => $group,
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
            'label' => 'Название группы',
            'attribute' => 'name'
        ],
    ]
]) ?>
<hr class="my-2">
<div class="row">
    <div class="col-8">
        <h4>Студенты в группе:</h4>
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
                    'label' => 'ФИО студента',
                    'value' => function($data){
                        return $data->profile->getFullName();
                    }
                ],
                [
                    'label' => 'Аккаунт',
                    'value' => function ($data) {
                        return Html::a('Ссылка', ['users/view', 'id' => $data->id]);
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Убрать',
                    'headerOptions' => ['width' => '80'],
                    'template' => '<center>{remove-student}</center>',
                    'buttons' => [
                        'remove-student' => function ($url, $model, $key) {
                            return Html::a('<i class="bi bi-person-dash-fill"></i>', $url);
                        }
                    ],
                ],
            ]
        ]) ?>
    </div>
    <div class="col-4">
        <h4>Добавить студента</h4>
        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'action' => Url::to(['groups/add-student']),
            'fieldConfig' => [
                'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            ]
        ]) ?>
        <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'groupId')->hiddenInput(['value' => $group->id]) ?>
        <?= $form->field($model, 'username')->widget(
            AutoComplete::className(), [
            'options' => [
                'class' => 'form-control form-control',
            ],
            'clientOptions' => [
                'source' => $data,
            ]
        ])->textInput(['placeholder' => 'Вводите имя'])->hint("Добавлять пользователей только с ролью 'Студент'") ?>
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-sm btn-outline-secondary']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>