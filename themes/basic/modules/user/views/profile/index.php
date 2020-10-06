<?php
    use yii\bootstrap\Tabs;
    use app\modules\user\models\PasswordChangeForm;
    $this->title = Yii::t('main','Профиль');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TITLE_PROFILE'), 
        'url' => ['/user/profile']];
?>
    
    <div class="row">
    <div class="col-md-12">
            <?=Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('main','Личные данные'),
                    'content' => $this->render('_profileForm',
                                            ['model'=>$model]),
                     'active' => true
                ],[
                    'label' => Yii::t('main','Смена пароля'),
                    'content' =>$this->render('_changePassword',
                                ['model'=>new PasswordChangeForm($model)]),
                ],[
                   
                    'label' => Yii::t('main','Контакты'),
                    'content' =>$this->render('contact/index',
                                ['dataProvider'=>$contactDataProvider]),
                ],
                [
                    'label' => Yii::t('main','Карта времени'),
                    'content' =>$this->render('clock/index',
                                ['dataProvider'=>$clockDataProvider]),
                   
                ],
                [
                    'label' => Yii::t('main','Безопасность'),
                    'content' =>$this->render('_security'),
                ],
            ]]);?>
    </div>
</div>


