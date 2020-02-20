<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$this->title = 'Не найдено (# 404)';
//$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode('Страница не найдена или недостаточно прав.')) ?>
    </div>

    <p>
        Выше возникла ошибка во время обработки Вашего запроса веб-сервером.
    </p>
    <p> 
        Пожалуйста, свяжитесь с нами, если Вы считаете, что это ошибка сервера. Спасибо.
    </p>

</div>
