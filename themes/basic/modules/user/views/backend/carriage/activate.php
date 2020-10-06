<?php
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
$this->title=Yii::t('app','Активация контракта #{id}',['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Контракты на перевозки'), 
            'url' => ['/user/backend/carriage-contract']];
$this->params['breadcrumbs'][] = Yii::t('app','Активация контракта #{id}',['id'=>$model->id]);
?>
<div class="internal-pages">
    <div class="col-sm-6 form-attributes">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <div class="internal-form">
        <br>
        <br>
        <?php $form = ActiveForm::begin();?>
            <?= $form->field($model, 'сontainer')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea() ?>
            <?= $form->field($model, 'route_description')->textarea() ?>
            <div class="row">
                <div class="col-sm-8">
                   <?= $form->field($model, 'route_sid')->textInput(['maxlength' => true]) ?> 
                </div>
                <div class="col-sm-4">
                    <br>
                    <?= Html::a('Создать маршрут','#',[
                        'class'=>'btn btn-default',
                        'id'=>'create-route-btn'])?>
                </div>
            </div>
            
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','Активировать'), 
                    ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
    Modal::begin([
        'header' =>false,
        'toggleButton' => 
            [
                'tag' => 'button',
                'id'=>'btn-called-modal-route-create',
                'label' => '',
            ]
    ]);
    echo '<iframe src="https://www.searates.com/ru/route-planner/frame?url=http://sealines.company/web/site/route_page" width="100%" height="600" frameborder="0" align="middle" scrolling="No"> </iframe>';
    Modal::end();
?>
<style>
    .modal-dialog{
       width:1100px;
    }
</style>
<script>
    $(document).ready(function(){
        $('#btn-called-modal-route-create').hide();
       $('#create-route-btn').click(function(e){
            $('#btn-called-modal-route-create').click();
            e.preventDefault();
            e.stopPropagation();
       }); 
    });
</script>





