<?php

namespace app\modules\user\models;

use yii\base\Model;
use Yii;

/**
 * Password reset request form
 */
class ResendActivationForm extends Model
{
    public $email;
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
                'filter' => ['status' => User::STATUS_WAIT],
                'message' => 'There is no user with such email.'
            ],
            ['email', 'timeFoSend'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'USER_EMAIL'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */

    public function timeFoSend()
    {
        if (!$this->hasErrors()) {
            $user = User::find()->where('email_confirm_token IS NOT NULL and status=:sw and email=:email', [':sw' => User::STATUS_WAIT, ':email' => $this->email])->one();

            if (time() < $user->updated_at + 3600) {
		$this->addError('email', 'Следующий запрос можно отправить через час.');
	    }
        }
    }

    public function sendEmail()
    {
        /* @var $user User */
        $user = User::find()->where('email_confirm_token IS NOT NULL and status=:sw and email=:email', [':sw' => User::STATUS_WAIT, ':email' => $this->email])->one();
        if ($user) {
		$user->generateEmailConfirmToken();
		if ($user->save()) {
                return \Yii::$app->mailer->compose('confirmEmail', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Активация аккаунта на сайте ' . Yii::$app->name)
                    ->send();
		}
        }
        return false;
    }
}
