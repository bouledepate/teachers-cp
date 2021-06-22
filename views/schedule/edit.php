<?php

/**
 * @var app\models\Schedule $data
 * @var app\models\Group $group
 * @var app\models\Discipline $disciplines
 * @var app\forms\ScheduleForm $model
 */

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use app\helpers\ScheduleHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

$js = <<< JS
$(document).ready(function(){
    $('#inputDay').on('change', function() {
    let url = "/schedule/get-disciplines?" + window.location.search.replace('?', '') ;
    console.log(url);
    let csrfToken = $('meta[name="csrf-token"]').attr("content");
    document.getElementById('scheduleform-day').value = $('#inputDay').val();
    $.ajax({
        type: 'post',
        url: url,
        data: {
            'day': $('#inputDay').val(),
            '_csrf': csrfToken
        },
        success: function(data){
            console.log(data);
            $('.schedule-display').html(data)
        },
        error: function(data){
             alert("Ошибка на стороне сервера.");
        }
    });
});
});
JS;

$this->registerJs($js);

$weekId = \Yii::$app->request->get('week');
$this->title = "Изменение расписания";
?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= Html::encode($this->title) ?> группы <?= $group->name ?>
            (<?= ScheduleHelper::weekName($weekId) ?>)</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="<?= Url::to(['schedule/view', 'id' => $group->id, 'week' => $weekId]) ?>"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-undo-alt"></i> Вернуться
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h5>Просмотреть расписание у группы по дням.</h5>
            <?= Html::beginForm(Url::to(['schedule/edit', 'id' => $group->id, 'week' => $weekId]), 'post', ['id' => 'schedule-by-day-form']); ?>
            <div class="form-group">
                <?= Html::dropDownList('day', $day, ScheduleHelper::dayList(), ['class' => 'form-control', 'id' => 'inputDay', 'prompt' => 'День недели']) ?>
                <label for="inputDay" class="text-secondary mt-2">Выберите день, чтобы вывести имеющееся расписание у
                    группы.</label>
            </div>
            <?= Html::endForm() ?>
            <?= \app\widgets\Alert::widget() ?>
        </div>
        <div class="col">
            <h6>Не могу найти дисциплину</h6>
            <p>Если в списке предложенных дисциплин какая-либо отсутствует, значит она не закреплена для данной группы.
                Перейдите по <a href="<?= Url::to(['groups/view', 'id' => $group->id]) ?>">этой ссылке</a>, чтобы
                назначить
                нужную дисциплину группе.</p>
            <h6>Не знаю какие дисциплины уже закреплены в расписании</h6>
            <p>По форме ниже вы можете посмотреть какие дисциплины уже определены в расписании группы по конкретным
                дням. Также можно вернуться к полному расписанию.
        </div>

    </div>
    <hr class="my-3">

<?php Pjax::begin([
    'id' => 'reload-pjax',
    'enablePushState' => false,
    'enableReplaceState' => false,
    'formSelector' => '#schedule-by-day-form',
    'submitEvent' => 'submit',]) ?>
    <div class="row">
        <div class="col">
            <?php $form = ActiveForm::begin([
                'layout' => 'default',
                'action' => Url::to(['schedule/add']),
                'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
            ]); ?>
            <?= $form->field($model, 'day')
                ->dropdownList(ScheduleHelper::dayList(), ['prompt' => 'День недели', 'disabled' => 'true']) ?>
            <?= $form->field($model, 'disciplineId')
                ->widget(Select2::className(), [
                    'bsVersion' => 4,
                    'data' => $disciplines,
                    'options' => ['placeholder' => 'Выберите дисциплину',
                        'autocomplete' => 'off'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => ['allowClear' => true]
                ]) ?>
            <?= $form->field($model, 'time')
                ->dropdownList(ScheduleHelper::timeList(), ['prompt' => 'Время проведения']) ?>
            <?= $form->field($model, 'groupId')->hiddenInput(['value' => $group->id]) ?>
            <?= $form->field($model, 'week')->hiddenInput(['value' => $weekId]) ?>
            <?= Html::submitButton('Внести в расписание', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col">
            <div class="schedule-display"><span class="text-muted">Здесь будет отображено расписание</span></div>
        </div>
    </div>
<?php Pjax::end(); ?>