<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'План';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Создать план подписки', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-bordered table-hover'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'plan_id',
            'plan_name',
            'plan_price',
            'plan_pub_amount',
            'plan_created_at',
            'plan_updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=> 'Action',
                'template' => '{update} {view} {delete}',
                'contentOptions' => ['style' => 'padding: 0px 10px 0px 10px; vertical-align: middle;'],
            ],
        ],
    ]); ?>
</div>
