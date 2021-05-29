<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $user yii\models\User
 * @var $profile yii\models\Profile
 * @var $items array
 * @var $params array
 */

$this->title = 'Редактирование пользователя ' . $user->username ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<div class="container">
    <?php $form = ActiveForm::begin(
        ['id' => 'user-update-form',
            'fieldConfig' => ['template' => "{label}\n{input}\n{hint}\n{error}"]
        ]) ?>
    <div class="form-row">
        <div class="col"><?= $form->field($user, 'username')->label('Имя пользователя') ?></div>
        <div class="col"><?= $form->field($user, 'email')->label('Электронная почта') ?></div>
    </div>
    <div class="form-row">
        <div class="col-6">
            <div class="form-group">
                <?= $form->field($user, 'role')->dropDownList($items, $params)->label('Статус пользователя') ?>
            </div>
        </div>
    </div>

    <hr class="my-2">
    <h4>Настройка профиля</h4>
    <div class="form-row">
        <div class="col"><?= $form->field($profile, 'first_name')->label('Имя') ?></div>
        <div class="col"><?= $form->field($profile, 'last_name')->label('Фамилия') ?></div>
    </div>
    <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end() ?>
</div>