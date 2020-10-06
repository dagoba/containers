<?php 
    use yii\bootstrap\Html;
    if (Yii::$app->getModule('googleauth')): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="row gcblock">
                <p class="col-sm-3 bgred"></p>
                <p class="col-sm-3 bgblue"></p>
                <p class="col-sm-3 bgyellow"></p>
                <p class="col-sm-3 bggreen"></p>
            </div>
            <h1><?= Yii::t('app','Двухэтапная аутентификация:');?></h1><br>
                <?php if (!Yii::$app->getModule('googleauth')
                            ->googleauthManager
                            ->isActive(Yii::$app->user->id)): ?>
                    <?php echo Html::a(Yii::t('app','Подключить'), [
                        '/googleauth/auth/connect'], [
                            'class' => 'btn btn-success ']); ?>
                <?php else: ?>
                    <?php echo Html::a(Yii::t('app','Отключить'), [
                        '/googleauth/auth/disconnect'], [
                            'class' => 'btn btn-danger ']); ?>
                <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

