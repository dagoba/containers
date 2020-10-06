<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;
?>          
    <div class="second_line">
        <p>Пожалуйста, введите Ваше имя пользователя или e-mail. Вы получите письмо со ссылкой для создания нового пароля.</p>
    </div>
<?php $form = ActiveForm::begin([
                                'action'=>Url::toRoute('/user/default/request-password-reset'),
                                'id' => 'only-modal-reset-password-form',
                                'enableClientValidation'=>true,
                                'validateOnBlur'=>false,
                                'validateOnChange'=>false,
                                'enableAjaxValidation'=>true,
                            ]); ?>
    <div class="row_textfield">
        <?= $form->field($model, 'email')?>
    </div> 
    <div class="row_textfield">
        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), 
                        [
                            'captchaAction' =>'/user/default/captcha',
                            'template' => 
                                '<div class="captha-row">
                                    <div class="captcha-image left">{image}</div>
                                    <div class="captha-field">{input}</div>
                                </div>',
                        ]) ?>
    </div> 
    <div class="left">
       <?= Html::submitButton('Получить новый пароль', [
           'class'=>'btn btn-primary']) ?>
    </div>  
<?php ActiveForm::end(); ?>
           
        
            
