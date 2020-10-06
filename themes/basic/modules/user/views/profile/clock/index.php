<?php
    use yii\helpers\Html;
    use yii\widgets\ListView;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <?=Html::a(Yii::t('app','Добавить'),['/user/profile/add-clock'],[
            'title'=>Yii::t('app','Добавить новые часы'),
            'class'=>'btn btn-success'
        ])?>
        <br>
        <br>
        <div class="clocks-block-conteiner">
            <?=ListView::widget([
                'dataProvider' => $dataProvider,
                'summary'=>'', 
                'itemView' => '_view',
                'emptyText'=>Yii::t('app','У вас пока нет часов'),
            ]);?>
        </div>
    </div>
</div>
<style>
    .clock-block{
        position: relative;
        padding-left: 10px;
        margin-right:15px;
    }
    .clock-block .delete-clock{
        position: absolute;
        left: 0;
        width: 10px;
        height: 10px;
        border:1px solid red;
        border-radius: 50%;
        font-size: 8px;
        text-align: center;
        color:#000;
    }
</style>
