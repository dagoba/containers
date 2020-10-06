<?php 
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>