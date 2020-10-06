<?php
    use yii\helpers\Url;
    $resetLink = Url::to(['/user/default/change-email-confirmation/', 
        'token' => $reset_token], true);
?>
<p>Здравствуйте</p>
<p>Кто-то, возможно вы, сделал запрос на смену email в системе <?=Yii::$app->name?></p>
<p>Для смены email перейдите по ссылке: </p>
<?= $resetLink ?>
<p>Если вы не делали запрос на смену email - проигнорируйте данное письмо.</p>
<p>С уважением, администрация сайта "<?=Yii::$app->name?>"!</p>
