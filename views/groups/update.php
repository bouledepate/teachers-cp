<?php

/**
 * @var $group app\models\Group
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Изменение группы ' . $group->name; ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<?= \app\widgets\Alert::widget(); ?>
<div class="">
    <div class="row">
        <div class="col-6">
            <?php $form = ActiveForm::begin([
                'layout' => 'default',
                'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
            ]) ?>
            <div class="form-row">
                <div class="col">
                    <?= $form->field($group, 'name')->textInput([
                        'placeholder' => 'Название группы', 'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <?= $form->field($group, 'speciality')->textInput([
                        'placeholder' => 'Специальность группы', 'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <?= Html::submitButton("Изменить", [
                        'class' => 'btn btn-primary'
                    ]); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
