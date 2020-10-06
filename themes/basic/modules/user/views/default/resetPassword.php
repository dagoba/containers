<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'TITLE_RESET_PASSWORD');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-pages">
    <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5 prform">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
            </div>
	    <div class="clear"></div>
            <br><br>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
