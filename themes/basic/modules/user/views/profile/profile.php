<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'TITLE_PROFILE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container"> 
    <div class="internal-pages">
        <h1 class="page-label"><?= Html::encode($this->title) ?></h1>
        <br>
        <br>
        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update'], ['class' => 'btn btn-color-blue','style'=>'margin-right:20px;']) ?>
        <?= Html::a(Yii::t('app', 'LINK_CHANGE_PASSWORD'), ['change-password'], ['class' => 'btn btn-color-white']) ?>
        <br>
        <br>
        <div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'email',
                ],
            ]) ?>
        </div>
    </div>
</div>