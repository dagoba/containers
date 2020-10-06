<?php
use yii\grid\GridView;
echo GridView::widget([
'dataProvider' => $dataProvider,
'summary'=>FALSE,
    'columns' =>[
            'value',[
                'attribute' => 'contact_id',
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data){
                    return $data->contacttype->name; 
                },
            ],
        ],
]);?>