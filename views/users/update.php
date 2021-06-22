<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $user app\models\User
 * @var $profile app\models\Profile
 * @var $items array
 * @var $params array
 */

$this->title = 'Редактирование пользователя ' . $user->username ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<?= \app\widgets\Alert::widget() ?>
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
        <div class="col"><?= $form->field($profile, 'first_name')->label('Имя')->textInput(['placeholder' => 'Не установлено']) ?></div>
        <div class="col"><?= $form->field($profile, 'last_name')->label('Фамилия')->textInput(['placeholder' => 'Не установлено']) ?></div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body bg-light">
                    <h6 class="card-title">Фотография профиля</h6>
                </div>
                <div class="card-body">
                    <?= Html::img($profile->getImage(), ['width' => 150, 'class' => 'rounded-lg']) ?>
                </div>
                <div class="card-body bg-light">
                    <a href="<?= Url::to(['users/set-image', 'id' => $profile->id]) ?>" class="btn btn-primary">Изменить</a>
                </div>
            </div>
        </div>
        <div class="col">
            <h6>Завершение редактирования профиля</h6>
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>