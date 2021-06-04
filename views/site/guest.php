<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Главная | Univer';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= $this->title ?></h1>
</div>
<div class="content">
    <div class="row">
        <div class="col">
            <h5>Univer 3.0</h5>
            <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium consequatur cum enim hic libero, molestias nemo officia quibusdam quos. Beatae eaque magni quod tempore voluptas! </p>
        </div>
        <div class="col">
            <h5>Почему наш аналог лучше?</h5>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, ad, consequuntur doloremque doloribus, eos est impedit itaque mollitia nesciunt nostrum numquam officia quae sint voluptate!</p>
        </div>
    </div>
    <div class="row">
        <div class="col" align="center">
            <img src="https://cdn3.f-cdn.com/contestentries/1504959/31626267/5ce56726afb07_thumb900.jpg" class="w-25 rounded">
            <h5>Начинай работу прямо сейчас</h5>
            <p>Перейдите по ссылке ниже и вводите свои регистрационные данные. В случае отсутствия регистрационных данных обратитесь к администратору.</p>
            <?= Html::a('Войти в систему', Url::to('auth/login'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>