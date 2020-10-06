<?php
use yii\widgets\DetailView;
use yii\bootstrap\Html;

$this->title=Yii::t('app','Просмотр информации о маршруте #{id}',['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Мои маршруты'), 
            'url' => ['/user/profile/my-routes']];
$this->params['breadcrumbs'][] = Yii::t('app','Просмотр информации о маршруте');
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
            <?=($model->route_sid!==null)? 
            '<iframe src="'.Yii::$app->params['apiSearatesLink'].$model->route_sid.'" width="100%" height="700" frameborder="0" align="middle" scrolling="No"> </iframe>'.
            DetailView::widget([
                 'model' => $model,
                 'attributes' => [
                    [
                        'attribute' => 'route_description',
                        'format' => 'raw',
                        'value' => $model->route_description,
                    ]
                 ],
             ])
            : '<p><b>'.Yii::t('app','Маршрут не подключен').'</b></p>';
           ?>
            <br>
           <?=DetailView::widget([
                 'model' => $model,
                 'attributes' => [
                    'id',
                    // [
                    //     'attribute' => 'user_id',
                    //     'format' => 'raw',
                    //     'value' => 'ID:'.$model->user_id.'/'.$model->user->email,
                    // ],
                    [
                        'attribute' => 'creator_id',
                        'format' => 'raw',
                        'value' => 'ID:'.$model->creator_id,
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
    </div>
</div>
