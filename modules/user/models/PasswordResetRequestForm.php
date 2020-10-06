<?php

namespace app\modules\user\models;

use yii\base\Model;
use Yii;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $verifyCode;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'app\modules\user\models\User',
                'filter' => ['status' => [User::STATUS_ACTIVE, User::STATUS_WAIT]],
                'message' => 'Пользователя с таким Email не существует.'
            ],
            ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
            //['email', 'requestStatus'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'USER_EMAIL'),
            'verifyCode'=>'Введите код:'
        ];
    }
/*
    public function requestStatus()
    {
        if (!$this->hasErrors()) {
            $user = User::find()->where('email_confirm_token IS NOT NULL and status=:sw and email=:email', [':sw' => User::STATUS_WAIT, ':email' => $this->email])->one();
	    if ($user) {
		if ($user->status == User::STATUS_ACTIVE) {
		    
		}
		elseif ($user->status == User::STATUS_WAIT and $user->email_confirm_token !== null) {
		    
		}
	    }
            else {
		$this->addError('email', 'Пользователя с таким Email не существует.');
	    }
        }
    }
*/
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => [User::STATUS_ACTIVE, User::STATUS_WAIT],
            'email' => $this->email,
        ]);
        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            if ($user->save()) {
                return \Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Запрос на смену пароля в системе ' . Yii::$app->name)
                    ->send();
            }
        }
        return false;
    }
}
