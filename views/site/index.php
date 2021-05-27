<?php

/* @var $this yii\web\View */

use yii\helpers\Html;


$app = Yii::$app;
$this->title = 'Главная | SCP';
?>
<div class="sub-header">
<div class="jumbotron">
  <h1 class="display-4">Students Control Panel</h1>
  <p class="lead">Данная панель управления предназначения для регулирования списков студентов и формирования учебных групп.</p>
  <hr class="my-4">
    <?php if(Yii::$app->user->isGuest): ?>
      <p>Чтобы приступить к работе, Вам необходимо авторизоваться.</p>
      <p class='text-muted font-weight-lighter' style="font-size: 12px;">В случае отсутствия регистрационных данных, обратитесь к администратору.</p>
      <a class="btn btn-primary btn-lg" href="login" role="button">Войти в систему</a>
    <?php else: ?>
    <p>Приветствуем, <strong><?= Html::encode($app->user->identity->username) ?></strong>. Желаем вам приятного администрирования.</p>
    <a class="btn btn-primary btn-lg" href="control-panel" role="button">Приступить к работе</a>
    <?php endif; ?>
</div>
</div>


