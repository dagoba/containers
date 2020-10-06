<?php
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$config = [
    'id' => 'basic',
    'language'=>'ru-RU',
    'name'=>'sealines',
    'sourceLanguage' =>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','MyGlobalClass'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' =>[
            'user' => [
                'class' => 'app\modules\user\Module',
            ], 
            'geo' => [
                'class' => 'app\modules\geo\Module',
            ],
           'finance' => [
                'class' => 'app\modules\finance\Module',
            ], 
            'googleauth' => [
                'class' => 'app\modules\googleauth\Module',
            ],
            'system' => [
                'class' => 'app\modules\system\Module',
            ], 
    ], 
    'components' => [
        'MyGlobalClass'=>[
            'class'=>'app\components\MyGlobalClass'
        ],
        'request' => [
        'baseUrl'=> '',
            'enableCsrfValidation' => false,
            'cookieValidationKey' => '-Id6HxMQGIX6ZJfDC5i9Zd4tFlLWJf5e',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'loginUrl' => ['/#login'],
            'enableAutoLogin' => true,
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'writeCallback' => function (){return ['user_id' => \Yii::$app->user->id];}
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'exportInterval' => 1,
                    'logVars' => ['_GET', '_POST', /*'_FILES', '_COOKIE', '_SESSION'*/],
                    'categories' => ['perfectmoney'],
                    'logFile' => '@app/runtime/logs/perfectmoney.log',
                    'maxFileSize' => 1024 * 1,
                    'maxLogFiles' => 100,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'exportInterval' => 1,
                    'logVars' => ['_GET', '_POST', /*'_FILES', '_COOKIE', '_SESSION'*/],
                    'categories' => ['advancedcash'],
                    'logFile' => '@app/runtime/logs/advancedcash.log',
                    'maxFileSize' => 1024 * 1,
                    'maxLogFiles' => 100,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'exportInterval' => 1,
                    'logVars' => ['_GET', '_POST', /*'_FILES', '_COOKIE', '_SESSION'*/],
                    'categories' => ['blockchain'],
                    'logFile' => '@app/runtime/logs/blockchain.log',
                    'maxFileSize' => 1024 * 1,
                    'maxLogFiles' => 100,
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],  
                'main*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'ru',
                    'fileMap' => [
                        'main' => 'main.php',
                    ],
                ],
                'slider*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'ru',
                    'fileMap' => [
                        'slider' => 'slider.php',
                    ],
                ],  
            ],
        ],
        'db' => $db,
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/basic',
                'baseUrl' => '@web/themes/basic',
                'pathMap' => [
                    '@app/views' => '@app/themes/basic',
                    '@app/modules' => '@app/themes/basic/modules',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'class' => 'app\components\widgets\MultiLang\Components\UrlManager',
            // // заменяем стандартный урл.менеджер на наш.
            // 'languages' => ['ru', 'en', 'de'],
            // //список языков на который переводим сайт
            // 'enableDefaultLanguageUrlCode' => true,    
            'rules' => [

            
           //     '' => 'site/index',
               //          '<_language:[\w\-]+>/<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
            'cabinet'=>'finance/payments/transactions',
            '<_a:(login|logout|signup|confirm-email|request-password-reset|reset-password|resend-activation)>' => 'user/default/<_a>',

            ],
        ],
    ],
    'params' => $params,
];
if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
