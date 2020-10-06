<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(!Yii::$app->user->id):?>
    <div style="height: 50px;"></div>
<?php endif;?>
<div class="container">
<div class="row">
<div class="col-md-12">
    <div class="col-md-2"></div>
    <div class="site-contact user_panel col-md-8">
        <h1 class="contact_h1"><?= Html::encode($this->title) ?></h1>
    <br>
    <br>
    <br>
        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

            <div class="alert alert-success">
               <?= Yii::t('main','Спасибо! Ваше сообщение отправлено.');?>
            </div>

        <?php else: ?>

            <p>
                <?= Yii::t('main','Если у вас есть какие-либо вопросы напишите нам.');?>
            </p>
    <br>
    <br>
            
                <div class=""><!-- col-lg-5 -->

                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'subject') ?>

                        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                        ]) ?>

                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('main','Отправить'), ['class' => 'btn btn-primary signup_but', 'name' => 'contact-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        <?php endif; ?>

    <div class="col-md-2"></div>
    </div>
    </div>
</div>