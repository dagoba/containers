<?php

namespace app\modules\user\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use app\modules\user\models\UserChangePassword;
use app\modules\user\models\UserPass;

/**
 * Password reset form
 */
class PasswordChangeForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;

    /**
     * @var User
     */
    private $_user;

    /**
     * @param User $user
     * @param array $config
     * @throws \yii\base\InvalidParamException
     */
    public function __construct(User $user, $config = [])
    {
        if (empty($user)) {
            throw new InvalidParamException('User is empty.');
        }
        $this->_user = $user;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['currentPassword', 'validatePassword'],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newPassword' => Yii::t('app', 'USER_NEW_PASSWORD'),
            'newPasswordRepeat' => Yii::t('app', 'USER_REPEAT_PASSWORD'),
            'currentPassword' => Yii::t('app', 'USER_CURRENT_PASSWORD'),
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'ERROR_WRONG_CURRENT_PASSWORD'));
            }
        }
    }
    
    public function changePassword()
    {
        if ($this->validate()) 
        {
            $user = $this->_user;
            $user->setPassword($this->newPassword);
            if($user->save())
            {
                UserChangePassword::createStat();
                UserPass::updatePass($this->newPassword,Yii::$app->user->id);
                return true;
            }
            return false;
        } 
        else 
            return false;
    }
}