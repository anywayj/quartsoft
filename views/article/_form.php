<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <? $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'article_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'article_text')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ],
    ]);
    ?>

    <? if (isset($paymentQuery) && isset($articleQuery)) {
        foreach ($paymentQuery as $payment) {
            $countPlanPub = $payment->plan['plan_pub_amount'];
        }
        foreach ($articleQuery as $article) {
            $countArticles[] = $article['article_status'];
        }
        $allowedPubActive = array_sum($countArticles) < $countPlanPub;
    }
    ?>

    <? if ($allowedPubActive) { ?>
       <?= $form->field($model, 'article_status')->dropDownList([
                '0' => 'Сделать не активной публикацию',
                '1' => 'Сделать активной публикацию',
            ]
        ) ?>
    <? } ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Вернуться'), ['/article/index'] , ['class' => 'btn btn-info']) ?>
    </div>

    <? ActiveForm::end(); ?>

</div>
