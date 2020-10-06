<?php
use yii\grid\GridView;
echo GridView::widget([
'dataProvider' => $dataProvider,
'summary'=>FALSE,
    'columns' => 
        [
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d-m-Y']
            ],[
                'attribute' => 'ip',
                'format'=>'raw',
                'class' => 'yii\grid\DataColumn',
            ],
            'location'
        ],
]);?>