<?php
 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 
$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста, заполните следующие поля для регистрации:</p>
    <div class="row">
        <div class="col-lg-5">
            <? $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($modelTwo, 'firstname') ?>
                <?= $form->field($modelTwo, 'lastname') ?>
                <div class="form-group">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?= Html::a(Yii::t('app', 'Вернуться'), ['/site/login'] , ['class' => 'btn btn-info']) ?>
                </div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>