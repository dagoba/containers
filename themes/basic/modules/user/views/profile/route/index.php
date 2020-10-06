<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use app\components\Trimmer;
$this->title=Yii::t('app','Контракты на перевозки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<!--         <br>
        <br>
        <br> -->
        <!-- <div class="container">
        <?php //$this->render('_search', ['model' => $searchModel]); ?>
        </div> -->

        <div class="col-md-12">
        	<h1><?=$this->title?></h1>
        	<div class="row gcblock">
				<p class="col-sm-3 bgred"></p>
				<p class="col-sm-3 bgblue"></p>
				<p class="col-sm-3 bgyellow"></p>
				<p class="col-sm-3 bggreen"></p>
			</div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
		            'class' => 'table table-striped table-bordered'
		        ],
                'summary'=>false,
                'emptyText'=>Yii::t('app','Нет контрактов'),
                'columns' => 
                    [

                        // [
                        //     'attribute' => 'user_id',
                        //     'class' => 'yii\grid\DataColumn',
                        //     'value' => function ($data){
                        //         return 'ID:'.$data->user_id.'/'.$data->user->email;},
                        // ],
                        [
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
                        ],
                        // [
                        //     'attribute' => 'description',
                        //     'format' => 'raw',
                        //     'class' => 'yii\grid\DataColumn',
                        //     'value' => function ($data){
                        //         return Trimmer::cutHelp($data->description);},
                        // ],
                        [
                            'attribute' => 'route_description',
                            'format' => 'raw',
                            'class' => 'yii\grid\DataColumn',
                            'value' => function ($data){
                                return Trimmer::cutHelp($data->route_description);},
                        ],
                        // [
                        //     'attribute' => 'Маршрут',
                        //     'format' => 'raw',
                        //     'class' => 'yii\grid\DataColumn',
                        //     'value' => function ($data){
                        //         return ($data->route_sid!=null)?Html::a('Просмотреть','#',[
                        //             'class'=>'show-modal-map',
                        //             'data-sid'=>$data->route_sid
                        //             ]): '-';},
                        // ],
                        [
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
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url,$model){
                                        return Html::a('Просмотреть',[
                                                    '/user/profile/view-route',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                            ],
                        ],
                    ],
                ]);?>
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