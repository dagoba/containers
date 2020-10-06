<?php

namespace app\modules\user\models;

use app\modules\user\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public $error_status = 0;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'USER_USERNAME'),
            'password' => Yii::t('app', 'USER_PASSWORD'),
            'rememberMe' => Yii::t('app', 'USER_REMEMBER_ME'),
        ];
    }

    /**
     * Validates the username and password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) 
        {
            if($this->isEmail())
                $user = $this->userByEmail();
            else
                $user = $this->userByLogin();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('app', 'ERROR_WRONG_USERNAME_OR_PASSWORD'));
            } elseif ($user && $user->status == User::STATUS_BLOCKED) {
                $this->addError('username', Yii::t('app', 'ERROR_PROFILE_BLOCKED'));
            } elseif ($user && $user->status == User::STATUS_WAIT) {
                $this->addError('username', Yii::t('app', 'ERROR_PROFILE_NOT_CONFIRMED'));
		$this->error_status = 3;
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        //gaAuthenticationManager->login();
        if ($this->validate()) {
            if($this->isEmail())
                return Yii::$app->user->login($this->userByEmail(), $this->rememberMe ? 3600*24*1 : 0);
            else
                return Yii::$app->user->login($this->userByLogin(), $this->rememberMe ? 3600*24*1 : 0);
        } else 
        {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
             if($this->isEmail())
                return $this->_user = $this->userByEmail();
            else
                return $this->_user = $this->userByLogin();
        }
        return $this->_user;
    }
    
    public function userByLogin()
    {
        if ($this->_user === false) 
            {$this->_user = User::findByUsername($this->username);}
        return $this->_user;
    }
    
    public function userByEmail()
    {
        if ($this->_user === false) 
            {$this->_user = User::find()->where(['email'=>$this->username])->one();}
        return $this->_user;
    }
    
    protected function isEmail()
    {
        if(filter_var($this->username, FILTER_VALIDATE_EMAIL))
            return true;
        return false;
    }
}
