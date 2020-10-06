<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
$this->title = Yii::t('app','Подключение Google Authentication');
$this->params['breadcrumbs'][] = $this->title;

foreach (get_included_files() as $filename){
}
header('Last-Modified: '. gmdate("D, d M Y H:i:s \G\M\T", filemtime($filename)));
?>
<br>
<br>

<div class="internal-pages" >
    <div class="form" style="width: 780px;">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <br>
        <br>
        <br>
        <?php $form = ActiveForm::begin(['id' => 'google-connect-form']); ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form_field col-sm-8">
                        <p class="field_label"><?=Yii::t('app','Введите сгенерированный код:')?></p>
                        <?= $form->field($model, 'code')->textInput(['maxlength' => 250])->label('') ?>
                        <br>
                          <?= Html::submitButton(Yii::t('app','Подключить'), [
                              'class' => 'btn btn-primary']) ?>
                    </div>
                    <div class="col-sm-4">
                        <?=Html::img($qrCodeUrl, [
                            'style' => 'border:1px solid #bababa; max-width:100%;']);?>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

