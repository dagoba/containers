<?php

namespace app\modules\user\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use app\modules\user\events\UserRegistrationEvent;
use Yii;
/**
 * Password reset form
 */
class EmailConfirmForm extends Model
{
    /**
     * @var User
     */
    const EVENT_EMAIL_CONFIRM = 'user.email.confirm';
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Ссылка активации e-mail недействительна.');
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Ссылка активации e-mail недействительна.');
        }
        parent::__construct($config);
    }

    /**
     * Confirm email.
     *
     * @return boolean if email was confirmed.
     */
    
    public function confirmEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        $user->removeEmailConfirmToken();
        if ($user->save()){
            $user->createAccount();
            $this->trigger(self::EVENT_EMAIL_CONFIRM, new UserRegistrationEvent($user));
            return true;
        }

    }
}
