<?php

namespace app\modules\user\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use app\modules\user\models\Useraccount;
use app\modules\geo\models\GeoCountry;
use app\modules\geo\models\GeoRegions;
use app\modules\geo\models\GeoCities;
use app\modules\user\models\UserPass;
use app\modules\user\models\UserCarriageContract;
use app\modules\user\models\UserContact;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $username
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;
    const STATUS_DELETED=3;

    const SCENARIO_SINGUP_ORDINARY = 'singup-ordinary';
    const SCENARIO_PROFILE = 'profile';
    const SCENARIO_MODER_UPDATE = 'moder-update';
    const SCENARIO_UPDATE_PROFILE= 'user-update-profile';
    const SCENARIO_UPDATE_AUTH_KEY='update-auth-key';
    const SCENARIO_UPDATE_AVATAR = 'update-avatar';

    const ROLE_NAME_MODER='Модератор'; 
    const ROLE_NAME_USER='Пользователь';

    const NUMBER_MODER=1;
    const NUMBER_USER=2;
    
    public $user_type;
    
    public $region_id;

    public $logsting;
    public $avatarFile;


    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return 
        [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z-0-9-_\. ]+$/u'],
            ['username', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'ERROR_USERNAME_EXISTS')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'ERROR_EMAIL_EXISTS')],
            ['email', 'string', 'max' => 255],

            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
            
            ['first_name', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z]+$/u'],
            ['first_name', 'string', 'max' => 50],
            
            ['last_name', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z]+$/u'],
            ['last_name', 'string', 'max' => 50],
            
            ['country_id', 'integer'],
            [['country_id'],'checkCountry'],
            
            ['region_id', 'integer'],
            [['region_id'],'checkRegion'],
            
            ['city_id', 'integer'],
            [['city_id'],'checkCity'],
          //  ['city_id', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z ]+$/u'],
            ['city_id', 'string', 'max' => 100],
            

            ['user_ip', 'string', 'max' => 255],
            //['user_ip', 'ip', 'negation' => true],
            
            ['useragent', 'string', 'max' => 255],

            [['avatarFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ['avatar', 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),[
            self::SCENARIO_SINGUP_ORDINARY => ['email','status','username'],
            self::SCENARIO_PROFILE => ['email'],
            self::SCENARIO_MODER_UPDATE=>['email','username','status',
                'first_name','last_name','country_id',
                'region_id','city_id'],
            self::SCENARIO_UPDATE_PROFILE=>['first_name','last_name','country_id',
                'region_id','city_id'],
            self::SCENARIO_UPDATE_AUTH_KEY=>['auth_key'],
             self::SCENARIO_UPDATE_AVATAR=>['avatarFile','avatar']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => Yii::t('app', 'USER_CREATED'),
            'updated_at' => Yii::t('app', 'USER_UPDATED'),
            'username' => Yii::t('app', 'USER_USERNAME'),
            'email' => Yii::t('app', 'USER_EMAIL'),
            'password'=> Yii::t('app', 'Пароль'),
            'details'=>Yii::t('app', 'Детали'),
            'status' => Yii::t('app', 'USER_STATUS'),
            'total_carriage'=>Yii::t('app', 'Заявки'),
            'userroles'=>Yii::t('main','Роль'),
            'first_name'=>Yii::t('main','Имя'),
            'last_name'=>Yii::t('main','Фамилия'),
            'country_id'=>Yii::t('main','Страна'),
            'region_id'=>Yii::t('main','Регион'),
            'city_id'=>Yii::t('main','Город'),
            'lastvisit'=>Yii::t('main','Последний логин'),
            'location'=>Yii::t('main','Место проживания')
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getStatusName()
    {
        $statuses = self::getStatusesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }
    
    public function getLocation(){
        if($this->city_id!=null){
           return $this->city->country->name_ru.' - '.
                $this->city->region->name_ru.' - '.
                $this->city->name_ru;
        } else if($this->country_id!=null){
        return $this->country->name_ru;
        } else{
            return '-';
        }
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => Yii::t('app', 'USER_STATUS_BLOCKED'),
            self::STATUS_ACTIVE => Yii::t('app', 'USER_STATUS_ACTIVE'),
            self::STATUS_WAIT => Yii::t('app', 'USER_STATUS_WAIT'),
            self::STATUS_DELETED => Yii::t('app', 'USER_STATUS_DELETE'),
        ];
    }
    
    public static function getRolesArray(){
        return [
            self::NUMBER_MODER => self::ROLE_NAME_MODER,
            self::NUMBER_USER => self::ROLE_NAME_USER,
        ];
    }

    public static function findIdentity($id){
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    public function getId(){
        return $this->getPrimaryKey();
    }

    public function getAuthKey(){
        return $this->auth_key;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public static function findByUsername($username){
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password){
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    public function reGenerateAuthKey(){
        $this->scenario=self::SCENARIO_UPDATE_AUTH_KEY;
        $this->generateAuthKey();
        return $this->save();
    }
    public function deleteAllSesions(){
        return  Yii::$app->db->createCommand()
                    ->delete(Yii::$app->session->sessionTable, ['user_id' => $this->id])
                    ->execute(); 
    }

    public static function findByEmailConfirmToken($email_confirm_token){
        return static::findOne(['email_confirm_token' => $email_confirm_token, 
            'status' => self::STATUS_WAIT]);
    }

    public function generateEmailConfirmToken(){
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    public function removeEmailConfirmToken(){
        $this->email_confirm_token = null;
    }

    public static function findByPasswordResetToken($token){
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => [self::STATUS_ACTIVE, self::STATUS_WAIT],
        ]);
    }

    public static function isPasswordResetTokenValid($token){
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    public function generatePasswordResetToken(){
        $this->password_reset_token = Yii::$app->security->generateRandomString(). 
            '_'.time();
    }

    public function removePasswordResetToken(){
        $this->password_reset_token = null;
    }

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->useragent=getenv("HTTP_USER_AGENT");
                $this->user_ip=  (Yii::$app->hasModule('geo'))?
                    Yii::$app->getModule('geo')->sypexgeoManager->getIP() : getenv("REMOTE_ADDR");
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }
    
    public function afterFind(){
        if($this->city_id!=NULL&&$this->region){
            $this->region_id=$this->region->id;
        } else{
            $this->region_id=NULL;
        }
        parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
       /* if($this->scenario ==self::SCENARIO_UPDATE_PROFILE){
            UserChangeProfile::saveChanges($this->logsting,$this->setLogString());
        }*/
 
    }
    
    public function setLogString(){
        return $this->logsting=json_encode([
            $this->first_name,
            $this->last_name,
            $this->country_id,
            $this->city_id,
            $this->email
        ], JSON_UNESCAPED_UNICODE);
    }
    /*------------------------------VALIDATORS---------------------------------*/
    public function checkCountry($attribute, $params){
        if(!empty($value)){
            if(GeoCountry::findOne(intval($value))==NULL)
                $this->addError($attribute, 'Неверно указана страна');
        }
    }
    
    public function checkRegion($attribute, $params) {
        if(!empty($value)){
            if(GeoRegions::findOne(intval($value))==NULL)
                $this->addError($attribute, 'Неверно указан регион');
        }
    }
    
    public function checkCity($attribute, $params){
        if(!empty($value)){
            if(GeoCities::findOne(intval($value))==NULL)
                $this->addError($attribute, 'Неверно указан город');
        }
    }
    /*------------------------------VALIDATORS---------------------------------*/
    
    /*-------------------------------GETTERS----------------------------------*/
    public function getCount_contract_created(){
        return $this->count_contract_by_status(UserCarriageContract::STATUS_CREATED);
    }
    
    public function getCount_contract_active(){
        return $this->count_contract_by_status(UserCarriageContract::STATUS_ACTIVE);
    }
    
    public function getCount_contract_completed(){
        return $this->count_contract_by_status(UserCarriageContract::STATUS_COMPLETED);
    }
    
    public function getCount_contract_canceled(){
        return $this->count_contract_by_status(UserCarriageContract::STATUS_CANCELED);
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getAccount(){
        return $this->hasOne(Useraccount::className(), ['user_id' => 'id']);
    }

    public function  getCountry(){
        return $this->hasOne(GeoCountry::className(), ['id' => 'country_id']);
    }
    
    public function  getCity(){
        return $this->hasOne(GeoCities::className(), ['id' => 'city_id']);
    }
    
    public function  getRegion(){
          return $this->hasOne(GeoRegions::className(), ['id' => 'region_id'])
            ->viaTable(GeoCities::tableName(), ['id' => 'city_id']);
    }

    public function getLastVisit(){
        return $this->hasOne(EntryStatistics::className(),['user_id' => 'id'])
                ->orderBy([EntryStatistics::tableName().'.created_at' => SORT_DESC]);
    }
    
    public function  getUserpass(){
        return $this->hasOne(UserPass::className(), ['user_id' => 'id']);
    }

    public function  getCarriagecontract(){
         return $this->hasMany(UserCarriageContract::className(), 
                                ['user_id' => 'id']);
    }

    public function  getContact(){
         return $this->hasMany(UserContact::className(), 
                                ['user_id' => 'id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    /*--------------------------------OTHER-----------------------------------*/
    public function createAccount(){
        $account = new Useraccount();
        $account->user_id = $this->getId();
        $account->save();
    }

    public function getHash(){
        $IP=getenv("REMOTE_ADDR");
        $U=getenv("HTTP_USER_AGENT");
        $H=getenv("HTTP_REFERER");
        return  md5($IP.$U.$H);
    }

     public function count_contract_by_status($status){
        return (new yii\db\Query())
            ->from(UserCarriageContract::tableName())
            ->where(['status'=> intval($status),'user_id'=>$this->id])
            ->count();
    }

    public function ckeckEmptyProfileData($checkCookie=true){
       if($checkCookie&&Yii::$app->request->cookies->getValue('hint-empty-profile')!=null){
           return true;
       }
        if(empty($this->country_id) || empty($this->city_id) ||
          empty($this->first_name) || empty($this->last_name) || !$this->contact){
            return false;
        }
        return true;
    }
    /*--------------------------------OTHER-----------------------------------*/
    
    
    public static function isSuperAdmin(){
        return (in_array(Yii::$app->user->id, [3]));
    }

    public function getAvatar_image(){
        if($this->existAvatar()){
            return Yii::getAlias('@web/web/uploads/avatars/'.$this->avatar);
        }
        return Yii::getAlias('@web/web/uploads/avatars/noavatar.png');
    }
    
    public function existAvatar(){
        if(!empty($this->avatar)&&file_exists (Yii::getAlias('@app/web/uploads/avatars/').$this->avatar)){
            return true;
        }
        return false;
    }
    
    public function removeAvatar(){
        $directory = Yii::getAlias('@app/web/uploads/avatars/');
        if (is_file($directory . DIRECTORY_SEPARATOR . $this->avatar)) {
            unlink($directory . DIRECTORY_SEPARATOR . $this->avatar);
            if(Yii::$app->db->createCommand()
                ->update(User::tableName(), ['avatar' => null], ['id'=>$this->id])
                ->execute()){
                        return true;
            }
        }
        return false;
    }
}
