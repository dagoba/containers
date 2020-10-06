<?php
use yii\helpers\Html;
$this->title = 'Добавление контракта пользователю "'.$user->email.'"';
$this->params['breadcrumbs'][] = ['label' =>'Все пользователи', 'url' => ['/user/backend/users']];
$this->params['breadcrumbs'][] = 'Добавление нового транспортного контракта';
?>
<div class="internal-pages">
    <div class="col-sm-6 form-attributes">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <div class="internal-form">
        <br>
        <br>
        <?=$this->render('_form',['model'=>$model])?>
    </div>
</div>


