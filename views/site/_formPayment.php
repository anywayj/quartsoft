<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Plan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-form">

    <p class="lead">Выберите удобную для вас подписку</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payment_user_id')->textInput() ?>

    <?= $form->field($model, 'payment_plan_id')->textInput() ?>

    <?= $form->field($model, 'payment_created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Купить', ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Вернуться'), ['/admin/plan/index'] , ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>