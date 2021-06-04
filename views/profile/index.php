<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $user app\models\User */

$this->title = 'Профиль ' . $user->username;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= $user->username ?></h1>
    <?php if (Yii::$app->user->can("editUser")): ?>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="<?= Url::to(['users/update', 'id'=>$user->id]) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil-square"></i> Изменить
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="content">
    <div class="row">
        <div class="col-5">
            <p class="alert alert-success">Статус в системе: <strong><?= $user->getRole() ?></strong></p>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $user->profile,
        'options' => [
            'class' => 'table table-sm table-bordered table-striped',
        ],
        'attributes' => [
            [
                'label' => 'Имя',
                'attribute' => 'first_name',
                'captionOptions' => ['width' => '20%'],
                'contentOptions' => ['width' => '80%'],
            ],
            [
                'label' => 'Фамилия',
                'attribute' => 'last_name'
            ],
            [
                'label' => 'Электронная почта',
                'value' => $user->email,
            ],
            [
                'label' => 'Группа',
                'attribute' => 'user.group.name',
                'visible' => (bool)Yii::$app->authManager->getAssignment('student', $user->id)
            ],
            [
                    'label' => 'Дисциплины',
                'value' => function($data) use ($user)
                {
                    $disciplines = [];
                    foreach($user->disciplines as $discipline){
                        $disciplines[] = $discipline->name;
                    }
                    return implode(', ', $disciplines);
                },
                'visible' => (bool)Yii::$app->authManager->getAssignment('teacher', $user->id)
            ]
        ],
    ])
    ?>
</div>

