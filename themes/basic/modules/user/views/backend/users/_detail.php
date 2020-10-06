<?php
use yii\widgets\DetailView;
use yii\bootstrap\Html;

$this->title='Подробная информация о "'.$model->username.'"';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['/user/backend/users']];
$this->params['breadcrumbs'][] = 'Подробная информация о пользователе';
?>
<div class="row">
    <div class="col-sm-12"> 
        <div class="col-sm-12">
            <h1><?= Html::encode($this->title) ?></h1>
            <br>
            <br>
            <div class="row">
                <div class="col-sm-2">
                    <?=Html::a('Редактировать',[
                        '/user/backend/user-edit','id'=>$model->id],[
                        'class'=>'btn btn-primary',
                        'title'=>'Редактировать информацию о пользователе'])?>
                </div>
                <div class="col-sm-2">
                    <?=Html::a('Разлогинить',[
                        '/user/backend/logout-all-devices','id'=>$model->id],[
                        'class'=>'btn btn-danger',
                        'title'=>'Выход со всех устройств'])?>
                </div>
                <div class="col-sm-2">
                    <?=Html::a('Зачислить/Списать',[
                        '/user/backend/new-financial-transaction','id'=>$model->id],[
                        'class'=>'btn btn-success',
                        'title'=>'Выполнить зачисление/списание'])?>
                </div>
                
            </div>
            <br>
            <br>
           <?=DetailView::widget([
                 'model' => $model,
                 'attributes' => [
                    'username',    
                    'email',
                    'first_name',
                    'last_name',
                    'location',[                      
                        'label' => 'IP адрес при регистрации',
                        'value' => $model->user_ip,
                    ],[                      
                        'label' => 'UserAgent при регистрации',
                        'value' => $model->useragent,
                    ],[
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d.m.y H:i']
                    ],[
                        'attribute' => 'updated_at',
                        'format' => ['date', 'php:d.m.y H:i']
                    ],
                 ],
             ]);
           ?>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h2>Контакты</h2>
            <br>
           <?=$this->render('_contact',['dataProvider'=>$dataProviderContact])?>
        </div>
    </div>
    <br>
    <br>
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h2>История входов</h2>
            <br>
           <?=$this->render('_enterStat',['dataProvider'=>$dataProviderEntry])?>
        </div>
    </div>
</div>
