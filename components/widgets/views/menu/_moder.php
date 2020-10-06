<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\components\widgets\MultiLang\MultiLang;
use app\modules\user\models\UserCarriageTicket;

    $new_tickets = UserCarriageTicket::find()->where(['status'=>UserCarriageTicket::STATUS_CREATED])->count(); 
    NavBar::begin([
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse nav-cabinet-user mymenu',
        ],
    ]);
    echo MultiLang::widget(['cssClass'=>'language langcss']);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels'=>false,
            'items' => [
                ['label' => Yii::t('main','Финансы'), 'items' => [
                    ['label' => Yii::t('main','Заявки на вывод'), 'url' => [
                        '/finance/backend/withdrawal-application']],
                    ['label' => Yii::t('main','Транзакции'), 'url' => [
                        '/finance/backend/transactions']],
                ]], 
                ['label' => Yii::t('main','Пользователи'), 'url' => ['/user/backend/users']],
                ['label' => Yii::t('main','Контракты'), 'url' => ['/user/backend/carriage-contract']],
                 ['label' => ($new_tickets>0)?
                    Yii::t('main','Заявки на контракты<span class="menu-counter">{count}</span>',[
                        'count'=>$new_tickets]) :
                    Yii::t('main','Заявки на контракты'),
                    'url' => ['/user/backend/carriage-tickets']],

                    ['label' => Yii::t('app','Выйти'), 'url' => [
                        '/user/default/logout'],
                        ],
            ],
        ]);
    NavBar::end();
?>

