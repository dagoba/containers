<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>          
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <?php $form = ActiveForm::begin([
                                                'action'=>Url::toRoute('/user/default/login'),
                                                'id' => 'only-modal-login-form',
                                                'enableClientValidation'=>true,
                                                'validateOnBlur'=>false,
                                                'validateOnChange'=>false,
                                                'enableAjaxValidation'=>true,
                                            ]); ?>
            <div class="row_textfield nclogin">
                <?= $form->field($model, 'username')->textInput(['placeholder'=>'Email'])->label(false) ?>
            </div> 
            <div class="row_textfield nclogin">
                <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Пароль'])->label(false) ?>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="check">
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?=  Html::a('Забыли пароль?','#',[
                         'title'=>'Забыли пароль',
                         'id'=>'reset-password-button',
                         'class'=>'pull-right rp-link'])?>
                </div>
            </div>
            <div class="row gcblock pt0">
                <p class="col-sm-3 bgred"></p>
                <p class="col-sm-3 bgblue"></p>
                <p class="col-sm-3 bgyellow"></p>
                <p class="col-sm-3 bggreen"></p>
            </div>
            <div class="button_links auto-height row fwb">
                 <div class="col-sm-6">
                     <?= Html::submitButton(Yii::t('app', 'USER_BUTTON_LOGIN'), [
                         'class' => 'w-100p btn-lg btn-success', 'name' => 'login-button']) ?>
                 </div>
                 <div class="col-sm-6 links">
                     <?=  Html::a('Регистрация',['/signup'],[
                         'title'=>'Регистрация',
                         'class'=>'w-100p btn btn-lg btn-primary'
                         ])?>
                 </div>    
            </div>
        <?php ActiveForm::end(); ?>
           
        
            
