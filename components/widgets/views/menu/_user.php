<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use app\components\widgets\MultiLang\MultiLang;
    use yii\bootstrap\Modal;
    use yii\bootstrap\Html;
    use yii\helpers\Url;
    NavBar::begin([
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse nav-cabinet-user mymenu',
        ],
    ]);
    echo MultiLang::widget(['cssClass'=>'language langcss']);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => Yii::t('app','Главная'), 'url' => ['/site/index']],
                // ['label' => Yii::t('app','Контакты'), 'url' => ['/site/contact']],
                // ['label' => Yii::t('app','Финансы'), 'items' => [
                //     ['label' => Yii::t('app','Баланс'), 'url' => [
                //         '/user/account']],
                //     ['label' => Yii::t('app','Заявки на пополнения'), 'url' => [
                //         '/finance/payments/deposit-application']],
                //     ['label' => Yii::t('app','Заявки на вывод'), 'url' => [
                //         '/finance/payments/withdrawal-application']],
                //      ['label' => Yii::t('app','История транзакций'), 'url' => [
                //         '/finance/payments/transactions']],
                // ]], 
                ['label' => Yii::t('app','Кабинет'), 'url' => ['/finance/payments/transactions']],
                ['label' => Yii::t('app','Профиль'), 'url' => ['/user/profile']],
                ['label' => Yii::t('app','Мои маршруты'), 'url' => ['/user/profile/my-routes']],
                // ['label' => Yii::t('app','Меню карт'), 'url' => ['/finance/cards']],
                ['label' => Yii::t('app','Выйти'), 'url' => ['/user/default/logout'],
                    ],
            ],
        ]);
    NavBar::end();
    Modal::begin([
            'header' =>'Внимание',
            'toggleButton' => 
                [
                    'tag' => 'button',
                    'id'=>'btn-called-modal-empty-info',
                    'label' => '',
                ]
        ]);
        echo 
        '<div style="font-size:14px;">
            <p >Для того что подать заяку на аренду контейнера вам 
                необходимо заполнить личные данные.</p>
            <br>
            <p>Для этого Вам необходимо перейти в раздел меню "Профиль" => "Личные данные".</p>
            <p>В этом окне вам необходимо заполнить все данные.</p><br>'.
            Html::img('@web/images/EqH1fIQ.jpeg',['style'=>'height:300px; margin:auto; display:block;']).'
            <br>
            <p>Также для того что бы наш менеджер смог с связаться с Вами, необходимо перейти 
                в раздел меню "Профиль" => "Контакты" и добавить контакты для связи с вами. </p>'.
            Html::img('@web/images/q9rPYHs.jpeg',['style'=>'height:200px; margin:auto; display:block;']).
            Html::a('Не показывать больше','#',['class'=>'center-block btn btn-danger dont-show-hint-btn']).'
            
        </div>';
        Modal::end();
if(!$user->ckeckEmptyProfileData()){
        Modal::begin([
            'header' =>'Внимание',
            'toggleButton' => 
                [
                    'tag' => 'button',
                    'id'=>'btn-called-modal-empty-profile',
                    'label' => '',
                ]
        ]);
        echo 
        '<div style="font-size:14px;">
            <p >Для того что подать заяку на аренду контейнера вам 
                необходимо заполнить личные данные.</p>
            <br>
            <p>Для этого Вам необходимо перейти в раздел меню "Профиль" => "Личные данные".</p>
            <p>В этом окне вам необходимо заполнить все данные.</p><br>'.
            Html::img('@web/images/EqH1fIQ.jpeg',['style'=>'height:300px; margin:auto; display:block;']).'
            <br>
            <p>Также для того что бы наш менеджер смог с связаться с Вами, необходимо перейти 
                в раздел меню "Профиль"</p>'.
            Html::img('@web/images/q9rPYHs.jpeg',['style'=>'height:200px; margin:auto; display:block;']).
            Html::a('Не показывать больше','#',['class'=>'center-block btn btn-danger dont-show-hint-btn']).'
            
        </div>';
        Modal::end();
    }
?>
<script>
$(document).ready(function(){
	
    var show_alert_empty_profile = "<?=($user->ckeckEmptyProfileData())? 'false' : 'true'?>";
    $('#btn-called-modal-empty-info').hide();
    $('#btn-modal-infosl').click(function(e){
        $('#btn-called-modal-empty-info').click();
        e.preventDefault();
        e.stopPropagation();
    });
    if(show_alert_empty_profile==="true"){
        $('#btn-called-modal-empty-profile').click();
    }
    $('.dont-show-hint-btn').click(function(e){
        var modal_window = $(this).closest('.modal-content');
            $.ajax({
                async:false,
                type: "GET",
                url: "<?=Url::toRoute('/user/profile/dont-show-hint')?>",
                data: "type=empty-profile",
                success: function(){ modal_window.find('.modal-header .close').click();},
                error:function(){alert("Error");}
            }); 
            e.preventDefault();
            e.stopPropagation();
        });
    $('#btn-called-modal-empty-profile').hide();
    
});
</script>