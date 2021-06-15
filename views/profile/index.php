<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $user app\models\User */

$this->title = 'Профиль ' . $user->username;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

    <h1 class="h3"><?= $user->username ?></h1>

    <?php if (Yii::$app->user->can("viewAdminCategories")): ?>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="<?= Url::to(['users/view', 'id' => $user->id]) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye"></i> Посмотреть
                </a>
                <a href="<?= Url::to(['users/update', 'id' => $user->id]) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="far fa-edit"></i> Изменить
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= \app\widgets\Alert::widget(); ?>
<div class="container">
    <div class="row">
        <div class="col-3">
            <div class="row">
                <div class="col" align="center">
                    <div class="row">
                        <div class="col">
                            <?= Html::img($user->profile->getImage(), ['width' => 200, 'height' => 200, 'class' => 'rounded-lg']) ?>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="h5"><?= \app\helpers\UserHelper::roleLabel($user->role) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
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
                        'value' => function ($data) use ($user) {
                            $disciplines = [];
                            foreach ($user->disciplines as $discipline) {
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
    </div>
</div>
