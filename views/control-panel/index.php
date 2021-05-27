<?php

use yii\helpers\Html;

$this->title = 'Панель управления';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= Html::encode($this->title) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <!--<div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>-->
    </div>
</div>
<div class="content">
    <h5 id="total-users"></h5>
    <hr class="my-2">
    <h5>Ваша роль:
        <?php
        $user_id = Yii::$app->user->getId();
        if(Yii::$app->authManager->getAssignment('admin', $user_id)){
            echo 'администратор.';
        } elseif(Yii::$app->authManager->getAssignment('teacher', $user_id)) {
            echo 'преподаватель.';
        } else {
            echo 'студент.';
        }
        ?>
    </h5>
</div>
<script>
    function declination(count) {
        count = Math.abs(count) % 100;
        let num = count % 10;
        if (count > 10 && count < 20) return `В системе зарегистрировано ${count} пользователей.`;
        if (num > 1 && num < 5) {
            return `В системе зарегистрировано ${count} пользователя.`
        } else if (num === 1) {
            return `В системе зарегистрирован ${count} пользователь.`
        } else {
            return `В системе зарегистрировано ${count} пользователей.`
        }
    }
    let totalUsers = parseInt(<?= $totalUsers ?>);
    document.getElementById('total-users').textContent = declination(totalUsers);
</script>

