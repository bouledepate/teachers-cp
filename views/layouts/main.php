<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<!doctype html>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">Univer</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse"
            data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <?php if (!\Yii::$app->user->isGuest): ?>
        <span class="text-secondary">Вы авторизованы, как <strong><?= Yii::$app->user->identity->username ?></strong></span>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="<?= Url::to(['auth/logout']) ?>">Выйти из системы</a>
            </li>
        </ul>
    <?php else: ?>
        <span class="text-secondary">Вы не авторизованы</strong></span>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="<?= Url::to(['auth/login']) ?>">Войти в систему</a>
            </li>
        </ul>
    <?php endif; ?>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item">
                            <a class="nav-link" href="/">
                                <i class="fas fa-home"></i>
                                Главная
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile">
                                <i class="fas fa-user"></i>
                                Ваш профиль
                            </a>
                        </li>
                    </ul>
                    <hr class="my-2">
                    <?php if (Yii::$app->user->can("viewAdminCategories")): ?>
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Для администратора</span>
                            <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
                                <span data-feather="plus-circle"></span>
                            </a>
                        </h6>
                        <ul class="nav flex-column mt-2">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['users/index']) ?>">
                                    <i class="fas fa-user-friends"></i>
                                    Пользователи
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['groups/index']) ?>">
                                    <i class="fas fa-users"></i>
                                    Группы
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['disciplines/index']) ?>">
                                    <i class="fas fa-graduation-cap"></i>
                                    Дисциплины
                                </a>
                            </li>
                        </ul>
                        <hr class="my-2">
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can("viewTeacherCategories")): ?>
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Преподавателю</span>
                            <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
                                <span data-feather="plus-circle"></span>
                            </a>
                        </h6>
                        <ul class="nav flex-column mt-2">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['estimates/index']) ?>">
                                    <i class="fas fa-book-open"></i>
                                    Журнал оценивания
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['certification/index']) ?>">
                                    <i class="fas fa-book-open"></i>
                                    Аттестация
                                </a>
                            </li>
                        </ul>
                        <hr class="my-2">
                    <?php endif ?>
                </div>
            <?php endif ?>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <?= $content ?>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

