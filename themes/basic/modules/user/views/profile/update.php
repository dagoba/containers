<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = Yii::t('app', 'TITLE_UPDATE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TITLE_PROFILE'), 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-pages">
    <div class="left form-attributes" style="width: 410px;">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <p class="note-text">Для смены email заполните следующие поля:</p>
        <div class="internal-form">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn-big btn-color-red']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
