<?php

namespace app\modules\googleauth;

class Module extends \yii\base\Module{

    public $controllerNamespace = 'app\modules\googleauth\controllers';
    
    const VERSION = '0.0.1';

    public function getDependencies() {
        return [
            'user',
        ];
    }

    public function getCategory() {
        return 'Google Authenticator';
    }

    public function getName() {
        return 'Google Authenticator';
    }

    public function getDescription() {
        return 'Google Authenticator module';
    }

    public function getAuthor() {
        return 'maikskofild';
    }

    public function getAuthorEmail() {
        return 'maikskofild1@gmail.com';
    }

    public function getVersion() {
        return self::VERSION;
    }

    public function getUrl() {
        return 'maikskofild1@gmail.com';
    }

    public function init(){
        parent::init();
         \Yii::configure($this, require(__DIR__ . '/config/config.php'));
    }
}
