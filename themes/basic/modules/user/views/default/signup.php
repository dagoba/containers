<?php
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'TITLE_SIGNUP');
$this->params['breadcrumbs'][] = $this->title;
?>

<div style="height: 50px;"></div>
<div class="container">
<div class="row">
<div class="col-md-12">
    <div class="col-md-3"></div>
    <div class="internal-pages col-md-6 user_panel" >
        <div class="form" style="">
            <h1 class="page-label contact_h1"><?= Html::encode($this->title) ?></h1>
            <br>
    	<span class="logpro">Для регистрации заполните следующие поля:</span>
            <br>
            <br>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <div style="">
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'captchaAction' => '/user/default/captcha',
                    'template' => '<div class="row">
                                    <div class="col-lg-3">{image}</div>
                                    <div class="col-lg-6 cap">{input}</div>
                                  </div>',
                ]) ?>
                <?= Html::submitButton(Yii::t('app', 'USER_BUTTON_SIGNUP'), [
                    'class' => 'btn btn-primary signup_but',
                    'name' => 'signup-button'
                    ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-md-3"></div>
    </div>
</div>
