<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'layout' => 'default',
    'options' => ['class' => 'form-signin'],
    'fieldConfig' => ['template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"]
])?>
<h1 class="h3 mb-3 font-weight-normal">Авторизация</h1>
<label for="inputUsername" class="sr-only">Username</label>
<?= $form->field($model, 'username', ['enableLabel' => false])->textInput(array(
        'placeholder' => 'Ваше имя', 'class'=>'form-control', 'id' => 'inputUsername')); ?>
<label for="inputPassword" class="sr-only">Password</label>
<?= $form->field($model, 'password', ['enableLabel' => false])->passwordInput(array(
        'placeholder' => 'Ваш пароль', 'class' => 'form-control', 'id' => 'inputPassword')); ?>
<?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня')?>

<?= Html::submitButton('Войти в систему', ['class' => 'btn btn-lg btn-primary btn-block']) ?>
<p class="mt-5 mb-3 text-muted">&copy; 2017-<?= date('Y') ?></p>

<?php ActiveForm::end(); ?>