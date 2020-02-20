<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = $model->article_name;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->article_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->article_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Вернуться'), ['/article/index'] , ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'article_id',
            'article_user_id',
            'article_name',
            [
                'attribute' => 'text',
                'format' => 'raw',
                'value' => function($data){
                    return $data->article_text;
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data){
                    return $data->article_status ? '<span class="text-success">Активна</span>' : '<span class="text-danger">Не активна</span>';
                },
            ],
            'article_created_at',
            'article_updated_at',
        ],
    ]) ?>

</div>
