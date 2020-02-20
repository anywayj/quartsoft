<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Plan */

$this->title = 'План подписки';
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formPayment', [
        'model' => $model,
    ]) ?>

</div>
