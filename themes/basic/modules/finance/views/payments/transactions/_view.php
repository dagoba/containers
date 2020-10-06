<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
use app\models\Curs;
use app\modules\user\models\UserCarriageTicket;
$this->title=Yii::t('app','Кабинет');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
    $evro = Curs::curs(Curs::EUR);
    $baks = Curs::curs(Curs::USD);
    $curs = $baks/$evro;
?>
<div class="row">
    <div class="col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div>
                    <?=\yii\helpers\Html::img($user->avatar_image,[
                            'alt'=>'avatar',
                            
                            'style' => 'max-width:120px; max-height:120px; border-radius:50%; border:2px solid #4285f4;'
                        ]);?>
                </div>
                <p><?= $user->last_name.' '.$user->first_name?></p><br>
                <?=(!$user->ckeckEmptyProfileData(false))?
                    Html::a(Yii::t('app','Создать контракт'),'#',[
                        'class'=>'btn btn-sm btn-info','id'=>'btn-modal-infosl']) :
                    Html::a('Создать контракт',[
                        '/user/profile/create-ticket'],[
                        'class'=>'btn btn-sm btn-info']);?>
                <br>
                <br>
                <p>Мои заявки: 
                    <span class="label label-warning"><?=Yii::t('main','Новые')?>:
                        <?=UserCarriageTicket::find()
                        ->where(['status'=>UserCarriageTicket::STATUS_CREATED,'user_id'=>Yii::$app->user->id])
                        ->count();?></span>&nbsp;
                    <span class="label label-success"><?=Yii::t('main','Обработанные')?>: 
                        <?=UserCarriageTicket::find()
                        ->where(['status'=>UserCarriageTicket::STATUS_PROCESSED,'user_id'=>Yii::$app->user->id])
                        ->count();?>
                    </span>&nbsp;
                    <span class="label label-danger"><?=Yii::t('main','Отмененные')?>:
                        <?=UserCarriageTicket::find()
                        ->where(['status'=>UserCarriageTicket::STATUS_REJECT,'user_id'=>Yii::$app->user->id])
                        ->count();?>
                    </span>
                </p>
                <br><br><p><?=Yii::t('app','Активные контракты:')?> <?=$user->Count_contract_active;?></p>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-6"><?=Yii::t('app','Баланс:')?></div>
                    <div class="col-sm-6 text-right">
                        <span style="font-family: 'HelveticaNeueCyr-Bold';"><?=$user->account->balance?> USD</span><br>
                        <span style="font-family: 'HelveticaNeueCyr-Bold';"><?=round($curs*$user->account->balance, 2);?> EUR</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <?=Html::a(Yii::t('app','Пополнить'),['/finance/payments/create-deposit-application'],[
                    'title'=>Yii::t('app','Создать заявку на пополнение'),
                    'class'=>'btn btn-success pull-right'
                ])?>
            </div>
            <div class="col-sm-2">
                <?=Html::a(Yii::t('app','Вывод'),['/finance/payments/create-withdrawal-application'],[
                    'title'=>Yii::t('app','Создать заявку на вывод денег'),
                    'class'=>'btn btn-primary pull-right'
                ])?>
            </div>
        </div>
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <p><?=Yii::t('app','История транзакций')?></p><br>
            <?php \yii\widgets\Pjax::begin(['timeout' => 10000, 
                'clientOptions' => ['container' => 
                    'pjax-container-widthrawal-aplication']]); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'summary'=>false,
                    'emptyText'=>Yii::t('app','Заявок на вывод нет'),
                    'columns' => 
                        [
                            'id',[
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:d-m-Y H:i']
                            ],[
                                'attribute' => 'amount',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data){
                                    return $data->amount; 
                                },
                            ],[
                                'attribute' => 'description',
                                'format'=>'raw',
                                'class' => 'yii\grid\DataColumn',
                            ],[
                                'attribute' => 'type_id',
                                'format' => 'raw',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data) {
                                    return $data->Type_name; 
                                },
                            ],[
                                'attribute' => 'paymentsystem_id',
                                'format' => 'raw',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data) {
                                    return $data->payment_system_name; 
                                },
                            ],[
                                'attribute' => 'status',
                                'format' => 'raw',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data) {
                                    return $data->status_name; 
                                },
                            ],
                        ],
                    ]);?>
        <?php \yii\widgets\Pjax::end(); ?>
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <table class="table">
                    <p style="font-size: 24px;"><?=Yii::t('app','Контакты')?></p>
                    <tr>                    
                        <td><span class="pull-left"><?=Yii::t('app','Почта:')?></span></td>
                        <td><span class="pull-right"><strong style="font-weight: bold;"><?=Yii::$app->params['adminEmail']?></strong></span></td>
                    </tr>
                    <tr>
                        <td><span class="pull-left" style="margin: 0 5px 5px 0;"><?=Yii::t('app','Адрес компании:')?></span></td>
                        <td><span class="pull-right" style="font-weight: bold;">Chiltern House, 45 Station Road, Henley-On-Thames, Oxfordshire, RG9 1AT</span></td>
                    </tr>
                    <tr>
                        <td><span class="pull-left"><?=Yii::t('app','Номер телефона:')?></span></td>
                        <td><span class="pull-right" style="font-weight: bold;">+442038087801, <?= Yii::t('app','+79680488079')?></span></td>
                    </tr>
                    <tr>
                        <td><span class="pull-left"><?=Yii::t('app','Скайп:')?></span></td>
                        <td><span class="pull-right" style="font-weight: bold;"><a href="skype:sealinesnow?chat">live:sealinesnow</a></span></td>
                    </tr>
                    <tr>
                        <td><span class="pull-left"><?=Yii::t('app','Ютуб канал:')?></span></td>
                        <td><span class="pull-right" style="font-weight: bold;"><a href="https://www.youtube.com/channel/UCh72UHI01D7kEpRDOuiCHEA?view_as=subscriber" target="_blank"><?= Yii::t('app','Подробнее');?></a></span></td>
                    </tr>
                </table>


            </div>
            <div class="col-sm-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d9927.21736951828!2d-0.9013477!3d51.5351484!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb868701451bc58a8!2sChiltern+House+Business+Centre+Limited!5e0!3m2!1sru!2sua!4v1504974678580" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>