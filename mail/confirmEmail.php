<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */
$confirmLink = Url::to(['confirm-email', 'token' => $user->email_confirm_token], true);
?>
<p>Спасибо за регистрацию акаунта в системе контектсных объявлений <?=Yii::$app->name?></p>
<p>Ваш логин в системе : <b><?= $user->username; ?></b></p> 
<p>Для подтверждения адреса перейдите по ссылке:  </p>
<?= $confirmLink ?>
<p><?= Yii::t('app', 'IGNORE_IF_DO_NOT_REGISTER') ?></p>
