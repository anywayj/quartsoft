<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Публикации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Создать публикацию', ['create'], ['class' => 'btn btn-success']) ?></p>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-bordered table-hover'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'name',
                    'value' => function($data){
                        return $data->article_name;
                    },
                ],

                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($data){
                        return $data->article_status ? '<span class="text-success">Активна</span>' : '<span class="text-danger">Не активна</span>';
                    },
                ],

                [
                    'attribute' => 'text',
                    'format' => 'raw',
                    'value' => function($data){
                        return $data->article_text;
                    },
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=> 'Action',
                    'template' => '{update} {view} {delete}',
                    'contentOptions' => ['style' => 'padding:0px 10px 0px 10px; vertical-align: middle;'],
                ],
            ],
        ]); ?>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h2>Ваши публикации</h2>
        </div>
        <? foreach($articleQuery as $article) { ?>
            <div class="block col-lg-4">
                <h3><?= $article['article_name'] ?></h3>

                <p><?= $article['article_text'] ?></p>

                <p><b>Автор:</b> <?= $article['firstname'] ?></p>
            </div>
        <? } ?>
        <? if (count($articleQuery) === 0) { ?>
            <div class="block col-lg-4">
                <p><b><?= 'No results found.' ?></b></p>
            </div>
        <? } ?>
    </div>
</div>
