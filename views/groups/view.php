<?php

/**
 * @var $group app\models\Group
 * @var $users app\models\User
 * @var $studentDataProvider yii\data\ActiveDataProvider
 * @var $disciplineDataProvider yii\data\ActiveDataProvider
 * @var $studentData array
 * @var $disciplineData array
 * @var $studentModel app\forms\AddStudentForm
 * @var $disciplineModel app\forms\AddDisciplineForm
 */

use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Группа ' . $group->name;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Информация о группе <?= $group->name ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['groups/update', 'id' => $group->id]) ?>" class="btn btn-sm btn-outline-success">
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
            'dataProvider' => $studentDataProvider,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered table-sm'
            ],
            'columns' =>
                [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'ФИО студента',
                        'value' => function ($data) {
                            return $data->profile->getFullName();
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Убрать',
                        'headerOptions' => ['width' => '80'],
                        'template' => '<center>{view} {remove-student}</center>',
                        'buttons' => [
                            'remove-student' => function ($url, $model, $key) {
                                return Html::a('<i class="bi bi-person-dash-fill"></i>', $url);
                            }],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'view') {
                                return Url::to(['users/view', 'id' => $model->id]);
                            } else {
                                return Url::to(['groups/remove-student', 'id' => $model->id]);
                            }
                        }
                    ],
                ]
        ]) ?>
    </div>
    <div class="col-4">
        <h4>Добавить студента</h4>
        <?php $form = ActiveForm::begin(['layout' => 'default',
            'action' => Url::to(['groups/add-student', 'id' => $group->id]),
            'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]]) ?>
        <?= $form->field($studentModel, 'studentId')->widget(Select2::className(), ['bsVersion' => 4,
            'data' => $studentData,
            'options' => ['placeholder' => 'Выберите студента',
                'multiple' => true,
                'autocomplete' => 'off'],
            'theme' => Select2::THEME_DEFAULT,
            'pluginOptions' => ['allowClear' => true],]) ?>
        <?= Html::submitButton("Прикрепить", ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<hr class="my-2">

<div class="row">
    <div class="col-8">
        <h4>Закреплённые дисциплины</h4>
        <?= GridView::widget(['dataProvider' => $disciplineDataProvider,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-sm'],
            'columns' => [['class' => 'yii\grid\SerialColumn'],
                ['label' => 'Дисциплина',
                    'attribute' => 'name'],
                ['class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'headerOptions' => ['width' => '80'],
                    'template' => '<center>{view} {remove-discipline}</center>',
                    'buttons' => ['remove-discipline' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', $url);
                    }],
                    'urlCreator' => function ($action, $model, $key, $index) use ($group) {
                        if ($action === 'view') {
                            return Url::to(['disciplines/view', 'id' => $model->id]);
                        } else {
                            return Url::to(['groups/remove-discipline', 'id' => $group->id, 'disciplineId' => $model->id]);
                        }
                    }],]]) ?>
    </div>
    <div class="col-4">
        <h4>Добавить дисциплину</h4>
        <?php $form = ActiveForm::begin(['layout' => 'default',
            'action' => Url::to(['groups/add-discipline', 'id' => $group->id]),
            'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]]) ?>
        <?= $form->field($disciplineModel, 'disciplineId')->widget(Select2::className(), ['bsVersion' => 4,
            'data' => $disciplineData,
            'options' => ['placeholder' => 'Выберите дисциплину',
                'multiple' => true,
                'autocomplete' => 'off'],
            'theme' => Select2::THEME_DEFAULT,
            'pluginOptions' => ['allowClear' => true],]) ?>
        <?= Html::submitButton("Прикрепить", ['class' => 'btn btn-primary']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>