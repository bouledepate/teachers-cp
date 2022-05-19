<?php

/**
 * @var $model app\forms\UploadImageForm;
 * @var $profile app\models\Profile;
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Изменение фотографии профиля' ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<?= \app\widgets\Alert::widget() ?>
<div class="">
    <?php $form = ActiveForm::begin(
        ['id' => 'user-update-form',
            'fieldConfig' => ['template' => "{label}\n{input}\n{hint}\n{error}"]
        ]) ?>
    <div class="form-row">
        <div class="col">
            <h6>Выберите новую фотографию профиля</h6>
            <div class="custom-file">
                <?= $form->field($model, 'image')->fileInput(['class' => 'custom-file-input', 'id' => 'customFile']) ?>
                <label class="custom-file-label" for="customFile">Выбрать файл</label>
            </div>
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="col" align="center">
            <div class="card">
                <div class="card-body bg-light">
                    <h6 class="card-title">Текущая фотография профиля</h6>
                </div>
                <div class="card-body">
                    <?= Html::img($profile->getImage(), ['width' => 150, 'class' => 'rounded-lg']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>