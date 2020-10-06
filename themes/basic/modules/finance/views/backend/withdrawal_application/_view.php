<?php
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use app\modules\finance\models\FinanceApplicationWithdrawal;
$this->title='Заявки на вывод';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-sm-12 user_panel">
        <h1 class="contact_h1"><?= Html::encode($this->title) ?></h1>
            <br>
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
            <br>
            <br>
            <div class="col-sm-12">            
                <?= GridView::widget([
                    'emptyText'=>'Заявок на вывод нет',
                    'dataProvider' => $dataProvider,
                        'columns' => [
                            'id',
                            [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:d-m-Y']
                            ],[
                                'attribute' => 'account_id',
                                'label'=>'Аккаунт ID/Email',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data){
                                    return $data->account_id.'/'.$data->user->email; 
                                },
                            ],
                            'amount',[
                                'attribute' => 'requisites',
                                'class' => 'yii\grid\DataColumn',
                            ],[
                                'attribute' => 'usercomment',
                                'format'=>'raw',
                                'class' => 'yii\grid\DataColumn',
                            ], [
                                'attribute' => 'description',
                                'format'=>'raw',
                                'class' => 'yii\grid\DataColumn',
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'class' => 'yii\grid\DataColumn',
                                'value' => function ($data){
                                    return $data->status_name; 
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{approve} {reject}',
                                'buttons' => [
                                    'approve' => function ($url,$model){
                                        if($model->canApproveWithdrawal())
                                            return Html::a('Одобрить',[
                                                'approve-withdrawal-application',
                                                'id'=>$model->id
                                            ],['class'=>'label label-primary']);
                                    },
                                    'reject' => function ($url,$model){
                                        if($model->canRejectWithdrawal()){
                                            return Html::a('Одобрить без подтверждения',[
                                                'direct-approve-withdrawal-application',
                                                'id'=>$model->id
                                            ],['class'=>'label label-primary']).'<br>'.Html::a('Отклонить',[''],[
                                                        'title'=>'Отклонить заявку', 
                                                        'class'=>
                                                            'action-with-comment label label-danger',
                                                        'data-id'=>$model->id
                                                    ]);
                                        }
                                    },
                                ],
                            ],
                        ],
                ]);?>
            </div>
    </div>
</div>
<?php
    Modal::begin([
        'header' => '<h2>Комментарий</h2>',
        'toggleButton' =>[
            'tag' => 'button',
            'id'=>'btn-called-modal-statuscomment',
            'label' => '',
        ]
    ]);
    echo $this->render('_statusForm',['model'=>new FinanceApplicationWithdrawal()]);
    Modal::end();
?>
<script type="text/javascript" data-cfasync="false">
$(document).ready(function(){
    $('#btn-called-modal-statuscomment').hide();
    $('.action-with-comment').click(function(){
        $('#btn-called-modal-statuscomment').click();
        $('#financeapplicationwithdrawal-moderid').val($(this).data('id'));
        return false;
    });
});
</script>