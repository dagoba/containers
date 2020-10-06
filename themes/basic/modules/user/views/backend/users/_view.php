<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\helpers\Url;
$this->title=Yii::t('app','Пользователи системы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12 user_panel">
        <h1  class="contact_h1"><?= Html::encode($this->title) ?></h1>
            <br>
            <br>
            <?=$this->render('_search', ['model' => $searchModel]); ?>
            <br>
            <br>
            <br>
            <div class="col-sm-12">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'emptyText'=>Yii::t('app','Нету пользователей'),
                    'columns' => [
                        'id',[
                            'attribute' =>'username',
                            'class' => 'yii\grid\DataColumn',
                            'format'=>'raw',
                            'value' => function ($data) {
                                return Html::a($data->username,[
                                    'login-by-user',
                                    'id'=>$data->id],[
                                    'title'=>Yii::t('app','Ввойти под пользователем {username}',
                                        ['username'=>$data->username]),
                                     'data-confirm'=>Yii::t('app','Подтверждая это действие вы выполните вход под пользователем "{username}". Вы действительно хотите продолжить?',['username'=>$data->username]),   
                                    ]);
                            },
                        ],
                        'email',[
                            'attribute' =>'password',
                            'class' => 'yii\grid\DataColumn',
                            'format'=>'raw',
                            'value' => function ($data){
                                return 
                                    '<div id="place-id-'.$data->id.'">'.
                                        Html::a(Yii::t('app','Показать'),'#',[
                                        'class'=>'show-pass-btn',
                                        'data-user-id' => $data->id,
                                        'title'=>Yii::t('app','Показать пароль'),
                                    ]).'</div>';
                            },
                        ],[
                            'attribute' =>'details',
                            'class' => 'yii\grid\DataColumn',
                            'format'=>'raw',
                            'value' => function ($data){
                                return Html::a(Yii::t('app','Подробнее'),
                                    ['user-detailed-information','id'=>$data->id],
                                    ['title'=>Yii::t('app','Посмотреть подробную информацию о пользователе')]);
                            },
                        ],[
                            'header'=>Yii::t('app','Контракты').
                                '<br><p class="label">'.
                                    '<span class="label label-info">'.Yii::t('app','Созданные').'</span>&nbsp;'.
                                    '<span class="label label-warning">'.Yii::t('app','Активные').'</span>&nbsp;'.
                                    '<span class="label label-success">'.Yii::t('app','Доставленные').'</span>&nbsp;'.
                                    '<span class="label label-danger">'.Yii::t('app','Отмененные').'</span>'.
                                '</p>',
                            'class' => 'yii\grid\DataColumn',
                            'format'=>'raw',
                            'value' => function ($data){
                                return 
                                    '<p class="label"><span title="'.Yii::t('app','Созданные').'" class="label label-info">'.$data->count_contract_created.'</span>&nbsp;'.
                                    '<span title="'.Yii::t('app','Активные').'" class="label label-warning">'.$data->count_contract_active.'</span>&nbsp;'.
                                    '<span title="'.Yii::t('app','Доставленные').'" class="label label-success">'.$data->count_contract_completed.'</span>&nbsp;'.
                                    '<span title="'.Yii::t('app','Отмененные').'" class="label label-danger">'.$data->count_contract_canceled.'</span></p>'.
                                    Html::a(Yii::t('app','Создать'),[
                                    '/user/backend/create-carriage-contract','user_id'=>$data->id],
                                    ['class'=>'label label-primary']);
                            },
                        ],
                        ],
                ]); ?>
            </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.show-pass-btn').click(function(e){
            ident = $(this).data('user-id');
            $.ajax({
                async:false,
                type: "GET",
                url: "<?=Url::toRoute('/user/backend/get-last-user-page')?>",
                data: "id="+ident,
                success: function(data){ $("#place-id-"+ident).html(data);},
                error:function(){alert("Error");}
            }); 
            e.preventDefault();
            e.stopPropagation();
        });
        
    });
</script>
