<?php
use yii\helpers\Html;
?>
<div class="clock-block pull-left">
    <?=Html::a('X',[
        '/user/profile/delete-clock','id'=>$model->id],[
        'title'=>Yii::t('app','Удалить часы'),
        'class'=>'delete-clock',
        ])?>
    <div class="time-zone"><?=$model->region->name_ru?></div>
    <div class="time" id="clock-n-<?=$model->id?>"></div>
</div>

<script>
$(document).ready(function(){
     var second=parseInt('<?=$model->date['s']?>');
    var minute= parseInt('<?=$model->date['i']?>');
    var hour = parseInt('<?=$model->date['H']?>');
    $('#clock-n-<?=$model->id?>').text(0);
    function clockAnimation(){
        second++;
        if (second>=60){
            second=0;
            minute++;
        }
        if (minute>=60){
            minute=0;
            hour++;
        }
        if (hour>23){
            hour=0;
            minute=0;
            second=0;
        }
        var cHour = hour;
        var cMinute = minute;
        var cSecond = second;
        if (second < 10){
            cSecond = "0"+ second;
        }
        if (minute < 10){
            cMinute =  "0"+minute;
        }
        if (hour < 10){
            cHour = "0" + hour;
        }
        $('#clock-n-<?=$model->id?>').html("<ul class='pager'><li><a class='btn btn-default navbar-btn hours'>"+cHour+"</a></li> : <li><a class='btn btn-default navbar-btn min'>"+cMinute+"</a></li> : <li><a class='btn btn-default navbar-btn sec'>"+cSecond+"</a></li></ul>");  
    };
    setInterval (clockAnimation,1000);
    clockAnimation();
});
</script>
