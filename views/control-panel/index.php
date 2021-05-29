<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Панель управления';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Профиль пользователя <?= Yii::$app->user->identity->username ?></h1>
</div>
<div class="content">
    <div class="row">
        <div class="col-4">
            <h5 class="alert alert-success">Ваша роль:
                <?php
                $user_id = Yii::$app->user->getId();
                if (Yii::$app->authManager->getAssignment('admin', $user_id)) {
                    echo 'администратор.';
                } elseif (Yii::$app->authManager->getAssignment('teacher', $user_id)) {
                    echo 'преподаватель.';
                } else {
                    echo 'студент.';
                }
                ?>
            </h5>
        </div>
    </div>
</div>

