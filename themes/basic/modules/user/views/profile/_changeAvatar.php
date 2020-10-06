<?php
use dosamigos\fileupload\FileUpload;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div id="user-avatar-example">
    <?php if($model->existAvatar()) :?>
    <div class="btn-delete-small delete-btn-avatar"
         
         data-url="<?=Url::toRoute(['/user/profile/delete-avatar','name'=>$model->avatar])?>">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
    </div>
    <?php endif;?>
    <?=Html::img($model->avatar_image,['alt'=>'avatar']);?>
</div>
<?= FileUpload::widget([
    'model' => $model,
    'attribute' => 'avatarFile',
    'url' => ['/user/profile/upload-avatar'], 
    'options' => ['accept' => 'xs'],
    'clientOptions' => [
        'maxFileSize' => 1
    ],
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                            if(data.result!== ""){
                                $("#user-avatar-example").html(
                                "<div class=\"btn-delete-small delete-btn-avatar\" data-url=\"'.Url::toRoute('/user/profile/delete-avatar').'?name="+data.result.deleteUrl+"\">"+
                                    "<span class=\"glyphicon glyphicon-remove-sign\" aria-hidden=\"true\"></span>"+
                                "</div>"+
                                "<img src=\'"+data.result.thumbnailUrl+"\'>");
                            } 
                            }',
        'fileuploadfail' => 'function(e, data) {
                                alert("Error");
                                
                            }',
    ],
]); ?>

<style>
    #user-avatar-example img{
        max-width:120px; 
        max-height:120px; 
        border-radius:50%; 
        border:2px solid #4285f4;
    }
    .btn-delete-small{
        color:#d9534f;
        font-size: 18px;
        cursor: pointer;
    }
</style>
<script>
$(document).ready(function(){
    $(document).on('click', '.delete-btn-avatar', function(){
        var request_url = $(this).data('url');
        $.ajax({
            async:false,
            type: "GET",
            url: request_url,
            success: function(){ $('#user-avatar-example').html('');},
            error:function(){alert("Error");}
        }); 
    });
});
</script>
