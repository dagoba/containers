<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use app\components\Trimmer;
$this->title=Yii::t('main','Заявки на контракты');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12 user_panel">
    <h1 class="contact_h1"><?=$this->title;?></h1>
        <br>
        <h2 style="font-size:17px; font-weight: bold;"><?=Yii::t('main','Новые заявки')?></h2>
        <br>
        <br>
        <div class="col-sm-12">
            <?= GridView::widget([
                'dataProvider' => $dataProviderNewTickets ,
                'summary'=>false,
                'emptyText'=>Yii::t('main','Нет заявок'),
                'columns' => 
                    [

                        [
                            'attribute' => 'user_id',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->user->email;},
                        ],[
                            'attribute' => 'description',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return ($data->description!=null)? $data->description : '-';
                            },
                        ],[
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:d.m.y H:i']
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->status_name;},
                        ],[
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{approve} {cancel} {delete}',
                            'buttons' => [
                                'approve' => function ($url,$model){
                                    if($model->canApprove())
                                        return Html::a(Yii::t('main','Обработать'),[
                                                    '/user/backend/approve-ticket',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                               'cancel' => function ($url,$model){
                                    if($model->canCancel())
                                        return Html::a(Yii::t('app','Отменить'),[
                                                    '/user/backend/cancel-ticket',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                                'delete' => function ($url, $model){
                                             return Html::a(Yii::t('app','Удалить'),[
                                                    '/user/backend/delete-ticket',
                                                    'id'=>$model->id,
                                                ],[
                                                'class'=>'btn',
                                                'data-pjax'=>0,
                                                'data-confirm'=>Yii::t('main','Вы действительно хотите удалить заявку?'),
                                                'data-method'=>'POST'
                                            ]);
                                    }
                            ],
                        ],
                    ],
                ]);?>
        </div>
        <br>
        <br>
        <h2 style="font-size:17px; font-weight: bold;"><?=Yii::t('main','Все заявки')?></h2>
        <br>
        <br>
        <?=$this->render('_search', ['model' => $searchModel]);?>
        <br>
        <br>
        <br>
           <br>
        <br>
        <br>
        <br>
        <br>
        <div class="col-sm-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider ,
                'summary'=>false,
                'emptyText'=>Yii::t('main','Нет заявок'),
                'columns' => 
                    [

                        [
                            'attribute' => 'user_id',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->user->email;},
                        ],[
                            'attribute' => 'description',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return ($data->description!=null)? $data->description : '-';
                            },
                        ],[
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:d.m.y H:i']
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->status_name;},
                        ],[
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{approve} {cancel} {delete}',
                            'buttons' => [
                                'approve' => function ($url,$model){
                                    if($model->canApprove())
                                        return Html::a(Yii::t('main','Обработать'),[
                                                    '/user/backend/approve-ticket',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                               'cancel' => function ($url,$model){
                                    if($model->canCancel())
                                        return Html::a(Yii::t('app','Отменить'),[
                                                    '/user/backend/cancel-ticket',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                                'delete' => function ($url, $model){
                                             return Html::a(Yii::t('app','Удалить'),[
                                                    '/user/backend/delete-ticket',
                                                    'id'=>$model->id,
                                                ],[
                                                'class'=>'btn',
                                                'data-pjax'=>0,
                                                'data-confirm'=>Yii::t('main','Вы действительно хотите удалить заявку?'),
                                                'data-method'=>'POST'
                                            ]);
                                    }
                            ],
                        ],
                    ],
                ]);?>
        </div>
    </div>
</div>
 