<?php

/**
 * @var $model app\forms\SignupForm;
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Создание пользователя';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<?= \app\widgets\Alert::widget() ?>
<div class="container">
    <div class="row">
        <div class="col-6">
            <?php $form = ActiveForm::begin([
                'layout' => 'default',
                'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
            ]) ?>
            <div class="form-row">
                <div class="col">
                    <?= $form->field($model, 'username')->textInput([
                        'placeholder' => 'Логин', 'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'email')->textInput([
                        'placeholder' => 'Электронная почта', 'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Пароль', 'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'password_repeat')->passwordInput([
                        'placeholder' => 'Повторите пароль', 'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'role')->dropdownList($model->items, $model->params) ?>
            </div>
            <?= Html::submitButton("Создать", [
                'class' => 'btn btn-primary'
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
