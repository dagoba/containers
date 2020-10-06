<?php 
    use  yii\widgets\ActiveForm;
    use yii\helpers\Html;
    $form = ActiveForm::begin(); 
?>
    <?= $form->field($model, 'сontainer')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'receivable_amount')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <div class="form-group">
        <?= Html::submitButton( $model->isNewRecord?
                Yii::t('app','Сохранить') : Yii::t('app','Редактировать'), 
                ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>