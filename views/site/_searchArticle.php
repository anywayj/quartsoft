<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\RecordUser;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">

    <? $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'article_user_id')->dropDownList(ArrayHelper::map(RecordUser::find()->all(), 'id', 'firstname'),
        [
            'prompt' => 'Все',
        ]
    )->label('Выберите автора') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
    </div>

    <? ActiveForm::end(); ?>

</div>
