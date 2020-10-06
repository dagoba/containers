<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use app\components\Trimmer;
$this->title=Yii::t('app','Контракты на перевозки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12 user_panel">
    <h1 class="contact_h1"><?=Yii::t('app','Контракты на перевозки');?></h1>
        <br>
        <br>
        <br>
        <?=$this->render('_search', ['model' => $searchModel]); ?>
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
                'dataProvider' => $dataProvider,
                'summary'=>false,
                'emptyText'=>Yii::t('app','Нет контрактов'),
                'columns' => 
                    [

                        [
                            'attribute' => 'user_id',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->user->email;},
                        ],[
                            'attribute' => 'amount',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->amount.'$';},
                        ],[
                            'attribute' => 'receivable_amount',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->receivable_amount.'$';},
                        ],[
                            'attribute' => 'сontainer',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                        ],[
                            'attribute' => 'description',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return Trimmer::cutHelp($data->description);},
                        ],[
                            'attribute' => 'route_description',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return Trimmer::cutHelp($data->route_description);},
                        ],[
                            'attribute' => 'Маршрут',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return ($data->route_sid!=null)?Html::a('Просмотреть','#',[
                                    'class'=>'show-modal-map',
                                    'data-sid'=>$data->route_sid
                                    ]): '-';},
                        ],[
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:d.m.y H:i']
                        ],
                        // [
                        //     'attribute' => 'updated_at',
                        //     'format' => ['date', 'php:d.m.y H:i']
                        // ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return $data->mood_status;},
                        ],[
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {edit} {pay} {execute} {cancel} {delete}',
                            'buttons' => [
                                'view' => function ($url,$model){
                                        return Html::a('Просмотреть',[
                                                    '/user/backend/view-carriage-contract',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                                'edit' => function ($url,$model){
                                    if($model->canEdit())
                                        return Html::a(Yii::t('app','Редактировать'),[
                                                    '/user/backend/edit-carriage-contract',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                                'pay' => function ($url,$model){
                                    if($model->canActive())
                                        return Html::a(Yii::t('app','Оплатить'),[
                                                    '/user/backend/active-carriage-contract',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                                'execute' => function ($url,$model){
                                    if($model->canComplete())
                                        return Html::a(Yii::t('app','Выполнить'),[
                                                    '/user/backend/complete-carriage-contract',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                                'cancel' => function ($url,$model){
                                    if($model->canCanceled())
                                        return Html::a(Yii::t('app','Отменить'),[
                                                    '/user/backend/canceled-carriage-contract',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },

                                'delete' => function ($url, $model){
                                        if($model->canDelete())
                                             return Html::a(Yii::t('app','Удалить'),[
                                                    '/user/backend/delete-carriage-contract',
                                                    'id'=>$model->id,
                                                ],[
                                                'class'=>'btn',
                                                'data-pjax'=>0,
                                                'data-confirm'=>Yii::t('app','Вы действительно хотите удалить контакт?'),
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
<?php
    Modal::begin([
        'header' =>false,
        'toggleButton' => 
            [
                'tag' => 'button',
                'id'=>'btn-called-modal-map',
                'label' => '',
            ]
    ]);
    echo '<div id="map-conteiner"></div>';
    Modal::end();
?>
<style>
    .modal-dialog{
       width:800px;
    }
</style>
<script>
    $(document).ready(function(){
       $('.show-modal-map').click(function(e){
            $('#map-conteiner').html('<iframe src="<?=Yii::$app->params['apiSearatesLink']?>'+$(this).data('sid')+'" width="100%" height="600" frameborder="0" align="middle" scrolling="No"> </iframe>');
            $('#btn-called-modal-map').click();
            e.preventDefault();
            e.stopPropagation();
       }); 
    });
</script>