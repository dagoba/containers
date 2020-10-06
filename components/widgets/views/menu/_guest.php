<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\bootstrap\Modal;
    use app\modules\user\models\LoginForm;
    use app\modules\user\models\PasswordResetRequestForm;
    use app\components\widgets\MultiLang\MultiLang;
    NavBar::begin([
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse mymenu navbar-fixed-top',
        ],
    ]);
    echo MultiLang::widget(['cssClass'=>'language langcss']);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => Yii::t('app','Главная'), 'url' => ['/site/index']],
                ['label' => Yii::t('app','Контакты'), 'url' => ['/site/contact']],
                ['label' => Yii::t('app','Вход'), 'url' =>'#','options'=>['class'=>'enter'], 'linkOptions' =>[
                    'id'=>'login-button']],
                ['label' => Yii::t('app','Регистрация'), 'url' => ['/user/default/signup']]
            ],
        ]);
    NavBar::end();
    Modal::begin([
        'id'=>'modal-login',
        'header' => '<h2>'.Yii::t('app','Вход').'</h2>',
        'toggleButton' => 
            [
                'tag' => 'button',
                'id'=>'btn-called-modal-login',
                'label' => '',
            ]
        ]);
        echo $this->render('@app/modules/user/views/default/modal_login',[
            'model'=>new LoginForm()],FALSE,TRUE);
    Modal::end();
    Modal::begin([
        'id'=>'modal-reset-password',
        'header' => '<h2>Восстановить пароль</h2>',
        'toggleButton' => [
                'tag' => 'button',
                'id'=>'btn-called-modal-reset-password',
                'label' => '',
            ]
    ]);
        echo $this->render('@app/modules/user/views/default/modal_resetPassword',[
            'model'=>new PasswordResetRequestForm()],FALSE,TRUE);
    Modal::end();
?>