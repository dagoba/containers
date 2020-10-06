<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'TITLE_CHANGE_PASSWORD');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TITLE_PROFILE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-pages">
    <div class="left form-attributes" style="width: 410px;">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <p class="note-text">Для смены пароля заполните следующие поля:</p>
        <div class="internal-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxlength' => true]) ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn-big btn-color-red']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
