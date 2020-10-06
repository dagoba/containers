<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<p>
    Пользователем ID: <b><?=$application->user_id?></b>; Email: <b><?=$application->user->email?></b> <br>
    была подана заяка на оформление контракта.
</p>
<?php if($application->description!==NULL):?>
<br>
<br>
<p><b>Детали заявки:</b></p>
<br>
<p>
    <?=$application->description?>
</p>
<?php endif; ?>
<br>
<p>Детали <?=Html::a(Url::to(['/user/backend/carriage-tickets'], true),Url::to(['/user/backend/carriage-tickets'], true))?></p>

