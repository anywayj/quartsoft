<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Plan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plan_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan_price')->textInput() ?>

    <?
        $massPubAmount = ['prompt' => 'Выберите количество'];
        for ($amount = 1; $amount < 10; $amount++) {
            $massPubAmount[$amount] = $amount;
        }
    ?>
    <?= $form->field($model, 'plan_pub_amount')->dropDownList($massPubAmount) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Вернуться'), ['/admin/plan/index'] , ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
