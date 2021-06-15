<?php

/***
 * @var app\models\Schedule $schedule
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use app\helpers\ScheduleHelper;
use yii\helpers\Url;

$this->title = 'Перенести занятие';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?> "<?= $schedule->discipline->name ?>"</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['schedule/edit', 'id' => $schedule->group_id, 'week' => $schedule->week]) ?>"
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-undo-alt"></i> Вернуться
            </a>
        </div>
    </div>
</div>
<?= \app\widgets\Alert::widget() ?>
<div class="container">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal'
    ]); ?>
    <?= $form->field($schedule, 'week')->dropdownList(ScheduleHelper::weekList(), ['prompt' => 'Тип недели']) ?>
    <?= $form->field($schedule, 'day')->dropdownList(ScheduleHelper::dayList(), ['prompt' => 'День недели']) ?>
    <?= $form->field($schedule, 'time')->dropdownList(ScheduleHelper::timeList(), ['prompt' => 'Время проведения']) ?>
    <?= Html::submitButton('Перенести занятие', ['class' => 'btn btn-success']); ?>
    <?php ActiveForm::end(); ?>
</div>