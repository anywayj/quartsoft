<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Plan */

$this->title = $model->plan_name;
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <? $form = ActiveForm::begin(); ?>

    <?= $form->field($modelPayment, 'payment_plan_id')->hiddenInput(['value' => $model->plan_id])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Купить', ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Вернуться'), ['/site/plan'] , ['class' => 'btn btn-info']) ?>
    </div>

    <? ActiveForm::end(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'plan_name',
            'plan_price',
            'plan_pub_amount',
        ],
    ]) ?>

</div>