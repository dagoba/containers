<?php

namespace app\components\widgets;

use yii\bootstrap\Widget;
use Yii;
use app\modules\user\models\User;

class Menu extends Widget
{
    public function init(){
        if(Yii::$app->user->isGuest){
            echo  $this->render('menu/_guest');
        } else{
            $user = User::findOne(Yii::$app->user->id);
            if(($role = Yii::$app->authManager->getRolesByUser($user->id))==null){
                return false;
            }
            switch (key($role)){
                case 'user': {
                    echo  $this->render('menu/_user',['user'=>$user ]);
                    break;
                }
                case 'moder':{
                    echo  $this->render('menu/_moder', ['user'=>$user ]);
                    break;
                }
                default : return false;
            }
        }
    }
}