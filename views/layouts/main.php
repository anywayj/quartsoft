<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\FrontendAsset;
use app\models\User;
FrontendAsset::register($this);

?>
<? $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <? $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <? $this->head() ?>
</head>
<body>
<? $this->beginBody() ?>
<? $isGuest = Yii::$app->user->isGuest ?>
<? $user = Yii::$app->user->identity->recordUser; ?>
    <div class="wrap">
        <? NavBar::begin([
                'brandLabel' => Yii::$app->name . ' <b>QuartSoft</b>',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Главная', 'url' => ['/site/index']],
                    ['label' => 'Админка', 'url' => ['/admin/plan/index'], 'visible' => Yii::$app->user->id === User::ADMIN],
                    $isGuest ? (
                        ['label' => 'Вход', 'url' => ['/site/login']]
                    ) : (
                        '<li>'
                        . Html::beginForm(['/site/logout'], 'POST')
                        . Html::submitButton(
                            'Выход (' . $user->firstname . ')',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>'
                    )
                ],
            ]);
            NavBar::end();
        ?>
        <? if (!$isGuest) { ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <br>
                        <div class="list-group">
                            <a class="list-group-item active">

                                <b><?= $user->firstname . ' ' . $user->lastname ?></b>
                            </a>
                            <a href="/article/index" class="list-group-item">
                                <span class="fa fa-list-alt"></span> Публикации
                            </a>
                            <a href="/site/plan" class="list-group-item">
                                <span class="fa fa-list-alt"></span> План подписки
                            </a>
                            <a href="/site/index" class="list-group-item">
                                <span class="fa fa-list-alt"></span> Главная
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <?= $content ?>
                   </div>
                </div>
            </div>
        <? } else { ?>
            <div class="container">
                <?= $content ?>
            </div>
        <? } ?>
    </div>
<? $this->endBody() ?>
</body>
</html>
<? $this->endPage() ?>
