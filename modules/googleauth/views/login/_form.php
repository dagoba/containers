<?php
//header('Last-Modified: '. gmdate("D, d M Y H:i:s \G\M\T", filemtime(array_pop(get_included_files())))); 
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app','Подтверждение входа через Google Authenticator');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-pages" >
    <div class="form" style="width: 780px;">
        <br>
        <br>
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <br>
	<span class="logpro"><?=Yii::t('app','Для входа введите код сформированный в  Google Authenticator:');?></span>
        <br>
        <br>
        <?php $form = ActiveForm::begin(['id' => 'form-google-login']); ?>
        <div style="width: 370px;">
            <?= $form->field($model, 'code') ?>
            <?= Html::submitButton(Yii::t('app','Войти'), [
                'class' => 'btn btn-primary',
                'name' => 'signup-button'
                ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
