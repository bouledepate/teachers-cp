<?php

/**
 * @var $model app\forms\SignupForm;
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Создание группы';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<div class="container">
    <div class="row">
        <div class="col-6">
            <?php $form = ActiveForm::begin([
                'layout' => 'default',
                'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
            ]) ?>
            <div class="form-row">
                <div class="col">
                    <?= $form->field($model, 'name')->textInput([
                        'placeholder' => 'Название группы', 'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col">
                    <?= Html::submitButton("Создать", [
                        'class' => 'btn btn-primary'
                    ]); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
