<?php

return [
    'params' => [
        'parameterName' => 'ref',
        'codeLength' => 10,
        'cookieExpire' => 180,
        'minWithdrawalAmount' => 5,
        'defaultProfitMethods' => ['percent']
    ],
    'components' => [
        'googleauthManager' => [
            'class'=>'app\modules\googleauth\components\GoogleauthManager'

        ],
        'gaAuthenticationManager' => [
            'class'=>'app\modules\googleauth\components\GAAuthenticationManager'
        ],
        'googleauthManagerListener' => [
            'class'=>'app\modules\googleauth\components\GoogleauthManagerListener'
        ],
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        'partnerManager' => [
            'class' => 'app\modules\partner\components\PartnerManager',
        ],
        'withdrawalManager' => [
            'class' => 'app\modules\partner\components\WithdrawalManager',
        ],
        'codeStatistic' => [
            'class' => 'app\modules\partner\components\CodeStatistic',
        ],
        'profitManager' => [
            'class' => 'app\modules\partner\components\profit\ProfitManager',
            'methods' => [
                'percent' => [
                    'class' => 'app\modules\partner\components\profit\MethodPercent',
                ],
                'cpa' => [
                    'class' => 'app\modules\partner\components\profit\MethodCPA',
                ],
            ],
        ],
        'transactionManager' => [
            'class' => 'app\modules\partner\components\TransactionManager',
        ],
    ],
];