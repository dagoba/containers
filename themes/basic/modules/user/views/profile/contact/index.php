<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
//$this->title='Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="row gcblock">
            <p class="col-sm-3 bgred"></p>
            <p class="col-sm-3 bgblue"></p>
            <p class="col-sm-3 bgyellow"></p>
            <p class="col-sm-3 bggreen"></p>
        </div>
        <?=Html::a(Yii::t('main','Добавить контакт'),['/user/profile/create-contact'],[
            'title'=>Yii::t('main','Добавить новый контакт'),
            'class'=>'btn btn-success'
        ])?><br><br>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => [
                        'class' => 'table table-striped table-bordered'
                    ],
                    'summary'=>false,
                    'emptyText'=>Yii::t('main','Нет контактов'),
                    'columns' => 
                        [
                            'value',[
                                'attribute' => 'contact_id',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data){
                                    return $data->contacttype->name; 
                                },
                            ],[
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit} {delete}',
                                'buttons' => [
                                    'edit' => function ($url,$model){
                                        if($model->canEdit())
                                            return Html::a(Yii::t('main','Редактировать'),[
                                                        '/user/profile/edit-contact',
                                                        'id' => $model->id
                                                    ],[
                                                        'title'=>Yii::t('main','Редактировать контакт'),
                                                        'class'=>'btn btn-xs btn-primary',
                                                        'data-pjax'=>0
                                                    ]);
                                        },
                                    'delete' => function ($url, $model){
                                            if($model->canDelete())
                                                 return Html::a(Yii::t('main','Удалить'),[
                                                        '/user/profile/delete-contact',
                                                        'id'=>$model->id,
                                                    ],[
                                                    'title' =>Yii::t('main','Удалить контакт'),
                                                    'class'=>'btn btn-xs btn-danger',
                                                    'data-pjax'=>0,
                                              //      'data-confirm'=>'Вы действительно хотите удалить контакт?',
                                              //      'data-method'=>'POST'
                                                ]);
                                        }
                                ],
                            ],
                        ],
                    ]);?>
        
    </div>
</div>