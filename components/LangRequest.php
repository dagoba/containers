<?php
namespace app\components;

use Yii;
use yii\web\Request;

class LangRequest extends Request
{
    private $_lang_url;

    public function init() {

            $language = '';
            $cookies = Yii::$app->response->cookies;

            if (($cookie = $cookies->get('language')) !== null) {
                $language = $cookie->value;

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
                else 
                    $language = 'en';
            }
            // добавление новой куки в HTTP-ответ
            $cookies->add(new \yii\web\Cookie([
                'name' => 'language',
                'value' => $language,
            ]));
            Yii::$app->language = $language;
        parent::init();
    }
}