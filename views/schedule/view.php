<?php

/**
 * @var app\models\Group $group ;
 * @var app\models\Schedule $data ;
 */

use app\helpers\ScheduleHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$weekId = \Yii::$app->request->get('week');
$this->title = "Расписание группы " . $group->name;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?> (<?= ScheduleHelper::weekName($weekId) ?>)</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['schedule/view', 'id' => $group->id, 'week' => $weekId ? 0 : 1]) ?>"
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-circle-right"></i> <?= ScheduleHelper::weekName($weekId ? 0 : 1) ?>
            </a>
            <?php if(\Yii::$app->user->can('viewAdminCategories')): ?>
            <a href="<?= Url::to(['schedule/edit', 'id' => $group->id, 'week' => $weekId]) ?>"
               class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-tasks"></i> <?= $data ? "Изменить" : "Заполнить" ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= \app\widgets\Alert::widget(); ?>
<div class="container">
    <div class="row row-cols-3">
        <?php foreach (ScheduleHelper::dayList() as $dayId => $dayName): ?>
            <div class="col p-1">
                <div class="card">
                    <div class="card-body bg-light">
                        <h5 class="card-title"><?= ScheduleHelper::dayName($dayId) ?> <?= ScheduleHelper::checkDisciplinesByDay($data, $dayId) ?></h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach (ScheduleHelper::timeList() as $timeId => $timeName): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="row">
                                    <div class="col-4">
                                        <span class="badge badge-secondary mr-3"><?= ScheduleHelper::timeName($timeId) ?></span>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <?= ScheduleHelper::displayDiscipline($data, $dayId, $timeId) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <?= ScheduleHelper::displayDisciplineTeacher($data, $dayId, $timeId) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>