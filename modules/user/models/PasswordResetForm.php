<?php

namespace app\modules\user\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use app\modules\user\models\UserPass;

/**
 * Password reset form
 */
class PasswordResetForm extends Model
{
    public $password;

    /**
     * @var User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'USER_NEW_PASSWORD'),
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword(){
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
	if ($user->status == User::STATUS_WAIT) {
	    $user->removeEmailConfirmToken();
	    $user->status = User::STATUS_ACTIVE;
	}
        if($user->save()){
            UserPass::updatePass($this->password,$user->id);
            return true;
        }else{
            return false;
        }
    }
}
