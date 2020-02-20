<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\assets\FrontendAsset;

$this->title = 'План подписки';
$this->params['breadcrumbs'][] = $this->title;
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
    <div class="site-index">
            <h1>План подписки</h1>

            <p class="lead">Выберите удобную для вас подписку</p>

            <? if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>
            <? } ?>

       <div class="body-content">
            <? foreach($paymentQuery as $payment) { ?>
               <? $subscribed = $payment['payment_user_id'] ;
                  $paymentDate = strtotime($payment['payment_created_at']);

                  if (isset($paymentDate)) {
                      $date = date('Y-m-d H:i:s', strtotime('-30 day'));
                      $allowedBuyPlan = strtotime($date) > $paymentDate;
                      $issetPlan = true;
                  }
                ?>
            <? } ?>

            <div class="row">
                <? foreach($planQuery as $plan) { ?>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <h2><?= $plan['plan_name'] ?></h2>

                        <p><a class="btn btn-default">Количество <?= $plan['plan_pub_amount'] ?></a></p>

                        <p>Цена: <b><?= $plan['plan_price'] ?></b></p>
                        <? if (empty($subscribed) || $allowedBuyPlan) { ?>
                            <? $issetPlan = false ?>
                            <?= Html::a('Купить &raquo;', ['view', 'id' => $plan['plan_id']], ['class' => 'btn btn-success']) ?>
                        <? } ?>
                    </div>
                <? } ?>

                <? if ($issetPlan) { ?>
                    <div class="col-lg-12">
                        <h3>У Вас уже есть активный план</h3>
                    </div>
                <? } ?>
            </div>
       </div>
    </div>
<? $this->endBody() ?>
</body>
</html>
<? $this->endPage() ?>


