<?php

namespace app\components;
use Yii;
class MyGlobalClass extends \yii\base\Component{
    public function init() {
        $language = '';
            if (isset($_COOKIE['language'])) {
                $language = $_COOKIE['language'];
            }
            else {
                 if(Yii::$app->hasModule('geo')){
                    if($geo=Yii::$app->getModule('geo')->sypexgeoManager->get()){
                        $language = ($geo['country']) ? $geo['country']['iso'] : '';
                    }
                }
                
                if(!empty($language))
                {
                    switch($language){
                        case 'RU':
                        case 'UA':
                        case 'BY':
                        case 'KZ':$language = 'ru';break;
                        case 'DE':$language = 'de';break;
                        default:$language = 'en';break;
                    }
                }
                else {
                    $language = "en";
                    SetCookie("language",$language,time()+86400*30,"/web");
                }
            }
            Yii::$app->language = $language;
        parent::init();
    }
}