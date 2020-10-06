<?php 
use yii\captcha\Captcha;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$form = ActiveForm::begin([
    'action'=>Url::toRoute('/user/default/simple-signup'),
    'id' => 'only-modal-simple-signup-form',
    'enableClientValidation'=>true,
    'validateOnBlur'=>false,
    'validateOnChange'=>false,
    'enableAjaxValidation'=>true,
]); ?>
<div style="">
    <?= $form->field($model, 'email')->textInput([
        'placeholder' =>Yii::t('app','Введите email'),
        'autocomplete'=>'off',
        'class'=>'input_form_registration'
    ])->label('');?>
    <?= $form->field($model, 'username')->textInput([
        'placeholder' =>Yii::t('app','Введите логин'),
        'autocomplete'=>'off',
        'class'=>'input_form_registration'
    ])->label('');?>
       <?= $form->field($model, 'password_repeat')->passwordInput([
        'placeholder' =>Yii::t('app','Введите пароль'),
        'autocomplete'=>'off',
        'class'=>'input_form_registration'
    ])->label('');?>
    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
        'captchaAction' => '/user/default/captcha',
        'template' => '<div class="row">
                        <div class="col-lg-3">{image}</div>
                        <div class="col-lg-6 cap pull-right"><input id="signupform-verifycode" class="input_form_registration form-control" name="SignupForm[verifyCode]" aria-invalid="true" type="text" placeholder ="'.Yii::t('app','Введите код').'"></div>
                      </div>',
    ])->label(''); ?>
    <?= Html::submitButton(Yii::t('app', 'USER_BUTTON_SIGNUP'), [
        'class' => 'about_company_a',
        'name' => 'signup-button'
        ]) ?>
</div>
<?php ActiveForm::end(); ?>
