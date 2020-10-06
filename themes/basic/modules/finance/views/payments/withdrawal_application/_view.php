<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
$this->title='Заявки на вывод';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
    <div class="col-md-1"></div>
    <div class="col-md-10 user_panel">
        <h1 class="contact_h1"><?= Html::encode($this->title) ?></h1>
            <br>
            <br>
            <?=Html::a('Создать заявку',['/finance/payments/create-withdrawal-application'],[
                'title'=>'Создать заявку на вывод денег',
                'class'=>'btn btn-primary'
            ])?>
            <br>
            <br>
            <br>
            <br>
            <?php \yii\widgets\Pjax::begin(['timeout' => 10000, 
                'clientOptions' => ['container' => 
                    'pjax-container-widthrawal-aplication']]); ?>
            <?=$this->render('_search', ['model' => $searchModel]); ?>
            <br>
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
                    'emptyText'=>'Заявок на вывод нет',
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
                                'attribute' => 'Описание',
                                'format'=>'raw',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data){
                                    return ($data->modercomment) ? $data->modercomment : $data->description;
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
            </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
    <div class="col-md-1"></div>
    </div>
</div>