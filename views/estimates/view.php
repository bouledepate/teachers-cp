<?php

/**
 * @var $group app\models\Group
 * @var $discipline app\models\Discipline
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model app\forms\AddEstimateForm
 */

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$groupMarks = Yii::$app->session->get('marksData');
$this->title = 'Журнал группы ' . $group->name ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Журнал группы <?= $group->name ?></h1>
    <p class="lead"><?= $discipline->name ?></p>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php $array = $dataProvider->getModels();
            echo Url::to(['estimates/remove-group-marks', 'id' => $group->id,  'discipline' => $discipline->id]) ?>" class="btn btn-sm btn-outline-secondary"><i
                        class="bi bi-person-plus-fill"></i> Удалить баллы группы
            </a>
        </div>
    </div>
</div>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Ошибка добавления!</strong> <?= Yii::$app->session->getFlash('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-8">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-bordered table-sm'
                ],
                'columns' =>
                    [
                        [
                            'headerOptions' => ['width' => '150'],
                            'label' => 'ФИО студента',
                            'value' => function ($data) {
                                return $data->profile->getFullName();
                            },
                        ],
                        [
                            'label' => '',
                            'format' => 'raw',
                            'value' => function ($data) use ($discipline) {
                                return \app\helpers\EstimateHelper::setCollapse($discipline->id, $data->id, false, true);
                            }
                        ],
                        [
                            'headerOptions' => ['width' => '100'],
                            'label' => 'Баллы',
                            'format' => 'raw',
                            'value' => function ($data) use ($discipline) {
                                return \app\helpers\EstimateHelper::setButton($data->id);
                            }
                        ]
                    ]
            ]) ?>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'action' => Url::to(['estimates/add-estimate']),
                    'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
                ]) ?>

                <?= $form->field($model, 'userId')->widget(Select2::className(), [
                    'bsVersion' => 4,
                    'data' => $data,
                    'options' => ['placeholder' => 'Выберите студента',
                        'autocomplete' => 'off'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => ['allowClear' => true]
                ])->hint('Выберите студента, которому нужно выставить оценку.') ?>
                <?= $form->field($model, 'value')
                    ->textInput(['type' => 'number', 'min' => '0', 'max' => '100', 'placeholder' => 'Кол-во баллов']) ?>
                <?= $form->field($model, 'createdAt')->textInput(['type' => 'date']) ?>
                <?= $form->field($model, 'authorId')->hiddenInput(['value' => \Yii::$app->user->getId()]) ?>
                <?= $form->field($model, 'disciplineId')->hiddenInput(['value' => $discipline->id]) ?>
                <?= Html::submitButton('Выставить', ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col">
            <div id="accordion">
                <?php foreach (\app\enums\MonthEnum::getMonths() as $key => $month) {
                    $days = cal_days_in_month(CAL_GREGORIAN, $key, date('Y'));
                    if (in_array($month, Yii::$app->session->get('includedMonths'))) {?>
                        <div class="card">
                            <div class="card-header" id="heading-<?= $key ?>">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse-<?= $key ?>" aria-expanded="true"
                                            aria-controls="collapse-<?= $key ?>">
                                        <?= 'Оценки за ' . strtolower($month) ?>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse-<?= $key ?>" class="collapse" aria-labelledby="heading-<?= $key ?>" data-parent="#accordion">
                                <div class="card-body table-responsive">
                                    <table class="table table-sm table-bordered" id="estimates-table">
                                        <thead>
                                        <tr class="table-secondary">
                                            <th>ФИО студента</th>
                                            <?php for ($day = 0; $day < $days; $day++) { ?>
                                                <th><?= $day + 1 ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($groupMarks as $student => $marks) { ?>
                                            <tr>
                                                <th><?= $student ?></th>
                                                <?php for ($day = 0; $day < $days; $day++) { ?>
                                                    <th ><?= $marks[$month][$day + 1] ?? '' ?></th>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php }} ?>
            </div>
        </div>
    </div>
</div>
