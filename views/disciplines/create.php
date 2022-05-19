<?php

/**
 * @var $model app\forms\CreateDisciplineForm
 * @var $data array
 */

use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

$this->title = 'Создать дисциплину'; ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>

<?= \app\widgets\Alert::widget(); ?>

<div class="">
    <div class="row">
        <div class="col-5">
            <?php $form = ActiveForm::begin([
                'layout' => 'default',
                'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
            ]) ?>
            <?= $form->field($model, 'name')->textInput([
                'class' => 'form-control', 'placeholder' => 'Название дисциплины'
            ]) ?>
            <?= $form->field($model, 'teacherId')->widget(Select2::className(), [
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
            ])->hint("Здесь можно назначить преподавателей на дисциплину") ?>
            <?= Html::submitButton("Создать", [
                'class' => 'btn btn-primary'
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
</div>

