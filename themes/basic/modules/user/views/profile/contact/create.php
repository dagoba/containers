<?php
use yii\helpers\Html;
$this->title = Yii::t('app','Добавление нового контакта');
$this->params['breadcrumbs'][] = ['label' =>Yii::t('app','Профиль'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <?=$this->render('_form',['model'=>$model])?>
	</div>
</div>