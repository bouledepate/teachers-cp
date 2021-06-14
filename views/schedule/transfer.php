<?php

/***
 * @var app\models\Schedule $schedule
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use app\helpers\ScheduleHelper;

$this->title = 'Перенести занятие';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
</div>
<?= \app\widgets\Alert::widget() ?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal'
]); ?>
<?= $form->field($schedule, 'week')->dropdownList(ScheduleHelper::weekList(), ['prompt' => 'Тип недели']) ?>
<?= $form->field($schedule, 'day')->dropdownList(ScheduleHelper::dayList(), ['prompt' => 'День недели']) ?>
<?= $form->field($schedule, 'time')->dropdownList(ScheduleHelper::timeList(), ['prompt' => 'Время проведения']) ?>
<?= Html::submitButton('Перенести занятие', ['class' => 'btn btn-success']); ?>
<?php ActiveForm::end(); ?>
