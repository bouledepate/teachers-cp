<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\search\UserSearch $searchModel
 */
$this->title = "Пользователи";
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?= Url::to(['users/create']) ?>" class="btn btn-sm btn-outline-success"><i
                        class="bi bi-person-plus-fill"></i> Создать
            </a>
        </div>
    </div>
</div>
<?= \app\widgets\Alert::widget() ?>
<div class="">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n{items}",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-sm'
        ],

        'columns' => [
            [
                'label' => 'Фамилия',
                'attribute' => 'profile.last_name'
            ],
            [
                'label' => 'Имя',
                'attribute' => 'profile.first_name'
            ],
            [
                'label' => 'Отчество',
                'attribute' => 'profile.second_name',
            ],
            [
                'label' => 'Электронная почта',
                'attribute' => 'email'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        if ($model->id === Yii::$app->user->id) {
                            return "<a class='text-primary' onclick='return alert(\"Вы не можете удалить самого себя.\")'><i class=\"fas fa-trash-alt\"></i></a>";
                        } elseif ($model->getRole() === 'admin')
                            return "<a href='$url' onclick='return confirm(\"Данный пользователь администратор. Удалить?\")'><i class=\"fas fa-trash-alt\"></i></a>";
                        else
                            return "<a href='$url'><i class=\"fas fa-trash-alt\"></i></a>";
                    }
                ]
            ],
        ],
    ]); ?>
    <?= \yii\bootstrap4\LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'maxButtonCount' => 3,
        'nextPageLabel' => '>>',
        'prevPageLabel' => '<<',
    ]) ?>
</div>
