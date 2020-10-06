<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
$this->title='Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12 user_panel">
        <h1 class="contact_h1"><?= Html::encode($this->title) ?></h1>
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
                            ],
                            [
                                'attribute' => 'account_id',
                                'header'=>'User',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data){
                                    return $data->user->email; 
                                },
                            ],
                            [
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
                            ],[
                                'attribute' => 'is_hidden',
                                'format' => 'raw',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data) {
                                    return $data->hidden_name; 
                                },
                            ],[
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {hidden}',
                            'buttons' => [
                              
                                'edit' => function ($url,$model){
                                 //   if($model->canEdit())
                                        return Html::a(Yii::t('app','Редактировать'),[
                                                    '/finance/backend/edit-transaction',
                                                    'id' => $model->id
                                                ],[
                                                    'class'=>'btn',
                                                    'data-pjax'=>0
                                                ]);
                                    },
                                'hidden' => function ($url,$model){
                                        return Html::a(
                                                ($model->is_hidden)?
                                                Yii::t('app','Сделать открытой'): Yii::t('app','Сделать скрытой'),[
                                                    '/finance/backend/edit-hidden',
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
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>