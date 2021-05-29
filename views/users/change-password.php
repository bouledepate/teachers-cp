<?php

/**
 * @var $model app\forms\ChangePasswordForm;
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Изменение пароля' ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<div class="container">
    <?php $form = ActiveForm::begin(
        ['id' => 'user-update-form',
            'fieldConfig' => ['template' => "{label}\n{input}\n{hint}\n{error}"]
        ]) ?>
    <div class="form-row">
        <div class="col"><?= $form->field($model, 'password')->label('Введите пароль') ?></div>
        <div class="col"><?= $form->field($model, 'password_repeat')->label('Повторите пароль') ?></div>
    </div>
    <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end() ?>
</div>