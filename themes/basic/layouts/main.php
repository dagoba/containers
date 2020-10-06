<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use app\components\widgets\Menu;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use app\components\widgets\Alert;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap">
        <?= Menu::widget() ?>
        <div class="container containersl">
            <?= Alert::widget() ?>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
           <?= $content ?>
           <?=(!Yii::$app->user->isGuest)? $this->render('@app/themes/basic/site/siteHard') : ''?>
        </div>
    </div>
<script>
$(document).ready(function(){
    var typeinv = window.location.hash,indType='';
    if(typeinv.indexOf('?') + 1){
        indType=typeinv.split('?')[0];
    } else {
        indType=typeinv;
    }
    if(indType.indexOf('#')+1){
        if(indType ==="#login"){
            $('#btn-called-modal-login').click();
        }
    }
    $('#btn-called-modal-login').hide();
    $('#btn-called-modal-reset-password').hide();
    $('#login-button').click(function(e){
        $('#btn-called-modal-login').click();
        e.preventDefault();
        e.stopPropagation();
    });
    $('#reset-password-button').click(function(e){
        $('#modal-login').find('.close').click();
        $('#btn-called-modal-reset-password').click();
        e.preventDefault();
        e.stopPropagation();
    });
});
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
