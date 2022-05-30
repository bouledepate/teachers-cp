<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Главная | СВГТК';
?>
<div class="text-center">
    <img src="<?= Yii::getAlias('@web') . '/images/logo.png' ?>" class="rounded mx-auto" height="350">
    <h5>Начинай работу прямо сейчас</h5>
    <p>Перейдите по ссылке ниже и вводите свои регистрационные данные. В случае отсутствия регистрационных данных
        обратитесь к администратору.</p>
    <?= Html::a('Войти в систему', Url::to('auth/login'), ['class' => 'btn btn-primary']) ?>
</div>