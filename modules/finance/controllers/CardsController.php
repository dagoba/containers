<?php

namespace app\modules\finance\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;


class CardsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
		'maxLength' => 4,
		'minLength'=> 4,
		'foreColor'=>0x474747,
            ],
        ];
    }

    public function actionIndex(){
        return 1;
    }
}
