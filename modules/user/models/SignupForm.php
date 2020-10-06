<?php

namespace app\modules\user\models;

use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\user\models\UserPass;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $verifyCode;
    public $roleType;

    const SCENARIO_SINGUP_ORDINARY = 'singup-ordinary';
    const SCENARIO_SINGUP_SIMPLE = 'singup-simple';

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z-0-9-_\. ]+$/u'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' =>
                Yii::t('app', 'ERROR_USERNAME_EXISTS')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 
                Yii::t('app', 'ERROR_EMAIL_EXISTS')],
            
            ['password', 'required'],
           // ['password', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z-0-9-_]+$/u'],
            ['password', 'string', 'min' => 6,'max'=>30],
            ['password', 'compare'],
            
            ['password_repeat', 'required'],
            ['password_repeat', 'string', 'min' => 6],
       
            
            ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
        ];
    }

    public function attributeLabels(){
        return [
            'username' => Yii::t('app', 'USER_USERNAME'),
            'email' => Yii::t('app', 'USER_EMAIL'),
            'password' =>'Повторить пароль',
            'verifyCode' => Yii::t('app', 'USER_VERIFY_CODE'),
            'password_repeat'=>'Пароль',
        ];
    }

    public function beforeValidate(){
	return parent::beforeValidate();
    }
    
    public function scenarios(){
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_SINGUP_ORDINARY=>['username','email','password',
                'password_repeat','verifyCode'],
            self::SCENARIO_SINGUP_SIMPLE=>['username','email','password_repeat','verifyCode']
        ]);
    }
    
    public function signup(){
        if ($this->validate()) {
            $user = new User();
            $user->scenario == User::SCENARIO_SINGUP_ORDINARY;
            if($this->scenario == self::SCENARIO_SINGUP_SIMPLE){
                 $user->setPassword($this->password_repeat);
            } else{
                  $user->setPassword($this->password);
            }
            $user->username = $this->username;
            $user->email = $this->email;
          
            $user->status = User::STATUS_WAIT;
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();
            if ($user->save()) {
                $userPass = new UserPass();
                $userPass->user_id = $user->id;
                $userPass->value = $this->password;
                $userPass->save();
                $authManager = \Yii::$app->authManager;
                $userRole = $authManager->getRole('user');
                $authManager->assign($userRole, $user->getId());
                $user->createAccount();
                Yii::$app->mailer->compose('confirmEmail', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Активация аккаунта на сайте ' . Yii::$app->name)
                    ->send();
                return TRUE;
            }
            return false;
        }
        return FALSE;
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*------------------------------VALIDATORS--------------------------------*/
    /*------------------------------VALIDATORS--------------------------------*/
}
