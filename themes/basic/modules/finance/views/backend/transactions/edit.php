<?php
use yii\helpers\Html;
$this->title = 'Редактирование транзакции';
$this->params['breadcrumbs'][] = ['label' =>'Транзакции', 'url' => ['/finance/backend/transactions']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-pages">
    <div class="col-sm-6 form-attributes">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <div class="internal-form">
        <?=$this->render('_form',['model'=>$model])?>
    </div>
</div>


