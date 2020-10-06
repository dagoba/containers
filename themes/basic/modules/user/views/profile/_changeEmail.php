<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\user\models\UserChangeEmail;
use app\components\Fonetika;
use yii\bootstrap\Modal;
?>
<?php if(\Yii::$app->session->hasFlash('messageChangeEmail')): ?>
    <div class="flash-success">
        <?=\Yii::$app->session->getFlash('messageChangeEmail');?>
    </div>
<?php else: ?>
    <?php if(!UserChangeEmail::checkTryLimit()) :?>
        <div class="note">
            <p>
                <?= Yii::t('main','Превышен лимит количества попыток смены Email.');?>
            </p>
            <p>
                <?= Yii::t('main','Максимальное количество попыток:');?> <?=UserChangeEmail::MAX_TRY_COUNT;?> 
                <?= Yii::t('main','за');?> <?=UserChangeEmail::TRY_PERIOD_DAYS?> 
                    <?=Fonetika::declOfNum(UserChangeEmail::TRY_PERIOD_DAYS, [
                        'день','дня','дней'])?>.
                Подтвердите ранее созданые заявки или попытайтесь позже.
            </p>
        </div>
    <?php else: ?>
        <?php $form = ActiveForm::begin([
                'action'=>Url::toRoute('/user/profile/change-email'),
                'id' => 'only-modal-change-email-form',
                'enableClientValidation'=>true,
                'validateOnBlur'=>false,
                'validateOnChange'=>false,
                'enableAjaxValidation'=>true]);?>
            <?= $form->field($model, 'after_email')
                    ->textInput(['maxlength' => true,'value'=>$email]) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('main','Изменить Email'), [
                    'class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
<?php endif; ?>
<?php
    Modal::begin([
        'header' => '<h2>Информация</h2>',
        'toggleButton' => 
            [
                'tag' => 'button',
                'id'=>'btn-called-modal-window-info',
                'label' => '',
            ]
    ]);
   echo '<p>На вашу старую почту было выслано подтверждение для смены Email.</p>'.
           '<br>'.
    '<p>Обращаем Ваше Внимание<br>на то, что ссылка действует в течении '.
           UserChangeEmail::LINK_PERIOD_HOUR.' '.
           Fonetika::declOfNum(UserChangeEmail::LINK_PERIOD_HOUR,['часа','часов']).'.</p>';
    Modal::end();
?>
<script type="text/javascript" data-cfasync="false">
$(document).ready(function(){$('#btn-called-modal-window-info').hide();});
var $formSettings=$('#only-modal-change-email-form');
$(document).on("beforeSubmit", $formSettings, function (e) {
    e.preventDefault();
    var form = $(this);
    var formData = form.serialize();
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data:formData,
        success: function() {
            $formSettings.detach();
            $('#btn-called-modal-window-info').click();
        },
        error: function () {alert("Error");}
    });
    e.preventDefault();
    e.stopPropagation();
});
$(document).on("submit",$formSettings, function (e){ e.preventDefault();return false;});
</script>