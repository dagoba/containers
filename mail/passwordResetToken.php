<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */
$resetLink = Url::to(['reset-password', 'token' => $user->password_reset_token], true);
?>
<p>Здравствуйте, ваш логин: <?=$user->username?></p>
<p>Кто-то, возможно вы, сделал запрос на смену пароля в системе <?=Yii::$app->name?></p>
<p>Для смены пароля перейдите по ссылке: </p>
<?= $resetLink ?>
<p>Если вы не делали запрос на смену пароля - проигнорируйте данное письмо.</p>
