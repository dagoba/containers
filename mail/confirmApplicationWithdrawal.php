<?php
    use yii\helpers\Url;
    $resetLink = Url::to(['/user/account/application-withdrawal-confirmation/', 'token' => $application->confirm_token], true);
?>
<p>Здравствуйте</p>
<p>Кто-то, возможно вы, сделал запрос на вывод денег в системе <?=Yii::$app->name?></p>
<p>Сумма: <b><?= $application->amount;?></b></p>
<p>Номер заявки: <b><?= $application->id;?></b></p>
<p>Для подтверждения заявки перейдите по ссылке: </p>
<?= $resetLink ?>
<p>Если вы не делали запрос на вывод - проигнорируйте данное письмо и заявка удалиться в течении 12 часов.</p>
<p>С уважением, администрация сайта "<?=Yii::$app->name?>"!</p>
