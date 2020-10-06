<?php
use yii\helpers\Html;
?>
<div class="btn-group <?= $cssClass; ?>">
    <a class="btn dropdown-toggle no-padding" data-toggle="dropdown" href="#">
        <span class="text-uppercase lang-first lang-<?= Yii::$app->language; ?>"></span>
        <span class='text-uppercase arrowlang'><?= Yii::$app->language; ?><i class='glyphicon glyphicon-menu-down'></i></span>
        
    </a>
    <ul class="dropdown-menu">
        <li class="item-lang">
            <?= Html::a('English', ['/site/lang','lang'=>'en']); ?>
        </li>
        <li class="item-lang">
            <?= Html::a('Русский', ['/site/lang','lang'=>'ru']); ?>
        </li>
        <li class="item-lang">
            <?= Html::a('Deutsch', ['/site/lang','lang'=>'de']); ?>
        </li>
    </ul>
</div>
