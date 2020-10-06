<?php
use yii\widgets\DetailView;
use yii\bootstrap\Html;

$this->title=Yii::t('app','Просмотр информации о контракте #{id}',['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Контракты на перевозки'), 
            'url' => ['/user/backend/carriage-contract']];
$this->params['breadcrumbs'][] = Yii::t('app','Просмотр информации о контракте');
?>
<div class="row">
    <div class="col-sm-12"> 
        <div class="col-sm-12">
            <h1><?= Html::encode($this->title) ?></h1>
            <br>
            <br>
            <div class="row">
                <?=$model->canActive()? Html::a(Yii::t('app','Оплатить заявку'),[
                    '/user/backend/active-carriage-contract','id'=>$model->id],[
                    'class'=>'btn btn-warning col-sm-2'
                ]) : ''?>
                <?=$model->canComplete()?Html::a(Yii::t('app','Выполнить заявку'),[
                    '/user/backend/complete-carriage-contract','id'=>$model->id],[
                    'class'=>'btn btn-primary col-sm-2'
                ]) : ''?>
                <?=$model->canCanceled()? Html::a(Yii::t('app','Отменить заявку'),[
                    '/user/backend/canceled-carriage-contract','id'=>$model->id],[
                    'class'=>'btn btn-primary col-sm-2'
                ]) : ''?> 
                <?=$model->canDelete()? Html::a(Yii::t('app','Удалить заявку'),[
                    '/user/backend/delete-carriage-contract','id'=>$model->id],[
                    'class'=>'btn btn-danger col-sm-2'
                ]) : ''?>
            </div>
            <br>
           <?=DetailView::widget([
                 'model' => $model,
                 'attributes' => [
                    'id',[
                        'attribute' => 'user_id',
                        'format' => 'raw',
                        'value' => 'ID:'.$model->user_id.'/'.$model->user->email,
                    ],[
                        'attribute' => 'creator_id',
                        'format' => 'raw',
                        'value' => 'ID:'.$model->creator_id.'/'.$model->creator->email,
                    ],[                      
                        'attribute' => 'amount',
                        'format' => 'raw',
                        'value' => $model->amount.'$',
                    ],[                      
                        'attribute' => 'receivable_amount',
                        'format' => 'raw',
                        'value' => $model->receivable_amount.'$',
                    ],
                    'description',
                    'сontainer',
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d.m.y H:i']
                    ],[
                        'attribute' => 'updated_at',
                        'format' => ['date', 'php:d.m.y H:i']
                    ],
                    [                      
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => $model->mood_status,
                    ]
                 ],
             ]);
            ?>
            <br>
            <br>
            <h2 style="font-size: 16px;"><?=Yii::t('app','Описание маршрута')?></h2>
            <br>
            <?=($model->route_sid!==null)? DetailView::widget([
                 'model' => $model,
                 'attributes' => [
                    [
                        'attribute' => 'route_sid',
                        'format' => 'raw',
                        'value' => $model->route_sid,
                    ],[
                        'attribute' => 'route_description',
                        'format' => 'raw',
                        'value' => $model->route_description,
                    ]
                 ],
             ]).
            '<iframe src="'.Yii::$app->params['apiSearatesLink'].$model->route_sid.'" width="100%" height="700" frameborder="0" align="middle" scrolling="No"> </iframe>'      
            : '<p><b>'.Yii::t('app','Маршрут не подключен').'</b></p>';
           ?>
        </div>
    </div>
</div>
