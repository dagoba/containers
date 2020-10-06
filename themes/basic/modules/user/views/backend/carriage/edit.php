<?php
use yii\helpers\Html;
$this->title = 'Редактирование контакта';
$this->params['breadcrumbs'][] = ['label' =>'Профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-pages">
    <div class="col-sm-6 form-attributes">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <div class="internal-form">
        <?=$this->render('_form',['model'=>$model])?>
    </div>
</div>


