<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
use app\models\Curs;
$this->title='Баланс';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-12">
<div class="col-md-2"></div>
    <div class="col-sm-8 user_panel">
        <h1 class="contact_h1"><?= Html::encode($this->title) ?></h1>
        <br>

        <?php 
                $evro = Curs::curs(Curs::EUR);
                $baks = Curs::curs(Curs::USD);
                $curs = $baks/$evro;
            ?>
        <div class="row">
            <div class="col-sm-6">Ваш текущий баланс: </div>
            <div class="col-sm-6">
            <span style="color:#0daf0a;"><?=$model->account->balance?>$</span><br>
             <span style="color:#e81e7f;"><?=round($curs*$model->account->balance, 2);?>&euro;</span>
            </div>
        </div>
            <br>
            <br>
            <?=Html::a('Пополнить',['/finance/payments/create-deposit-application'],[
                'title'=>'Создать заявку на пополнение',
                'class'=>'btn btn-primary'
            ])?>
            <br>
    </div>
    <div class="col-md-2"></div>
    </div>
</div>