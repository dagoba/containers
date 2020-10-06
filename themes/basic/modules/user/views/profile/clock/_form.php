<?php
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
$this->title = Yii::t('app','Добавление новых часов');
$this->params['breadcrumbs'][] = ['label' =>Yii::t('app','Профиль'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <div class="internal-form">
        <?php $form = ActiveForm::begin(); ?>
            <?php echo $form->field($model, 'region_id')->widget(Select2::classname(), [
                'data' => $model->regionsList(),
                'options' => ['placeholder' => Yii::t('app','Выберите регион ...')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
            
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','Сохранить'), ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

