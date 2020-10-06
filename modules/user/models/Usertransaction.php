<?php

namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use app\modules\user\models\User;
use app\modules\user\models\Useraccount;
use app\modules\user\models\Userchangebalance;
use app\components\Qrcode;
/**
 * This is the model class for table "tbl_usertransaction".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $type_id
 * @property string $amount
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $description
 * @property string $curs
 * @property string $ps_tr_id
 * @property integer $paymentsystem_id
 * @property integer $status
 */
class Usertransaction extends ActiveRecord
{
    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAWAL = 3;
    const TYPE_INTERNAL_WITHDRAWAL=4;
    const TYPE_INTERNAL_DEPOSIT=5;
    
    const TYPE_DEPOSIT_HAND=7;
    const TYPE_WITHDRAWAL_HAND=6;
    
    const PAYMENT_LOCAL = 0;
    const PAYMENT_WMR=1;
    const PAYMENT_WMZ=2;
    const PAYMENT_PERFECT_MONEY=3;
    const PAYMENT_YANDEX_MONEY=4;
    const PAYMENT_QIWI=5;
    const PAYMENT_VISA_OTHERS = 6;
    const PAYMENT_MASTER_CARD_OTHERS = 7;
    const PAYMENT_INTERKASSA=8;
    const PAYMENT_ADVANCED_CASH=9;
    const PAYMENT_BTC=10;

    const STATUS_CREATED = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_REJECT = 3;
    const STATUS_CANCELED = 5;
    const STATUS_MODER = 7;

    /* PerfectMoney */
    var $pf_acc;
    var $pf_name;
    var $pf_units;
    var $pf_pay;
    var $pf_nopay;
    var $pf_paymeth;
    var $pf_nopaymeth;
    var $pf_status;
    var $pf_hash;
    var $pf_amount;
    
    /* AdvancedCash */
    var $adv_acc;
    var $adv_name;
    var $adv_currency;
    var $adv_amount;
    var $adv_orderid;
    var $adv_hash;

    /*BTC*/
    var $btc_xpub;
    var $btc_secret;
    var $btc_api_key;
    var $btc_form;
    var $btc_amount;
    var $btc_curs;

    const SCENARIO_DEPOSIT = 'deposit-transaction';
    const SCENARIO_WITHDRAWAL = 'withdrawal-transaction';
    const TYPE_DEPOSIT_PS= 'deposit-transaction-ps';
    const SCENARIO_MODER_UPDATE = 'moder-edit';
    const SCENARIO_CHANGE_HIDDEN = 'change-hideen';
    
    
    const SCENARIO_APPROVE = 'approve-transaction';
    
    public static function tableName()
    {
        return '{{%user_transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return 
        [
	    ['amount', 'required'],
	    ['amount', 'number','min' => 0.01],
	
            ['status', 'required'],
            ['status', 'default', 'value' => self::STATUS_CREATED],
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::statusArray())],
            
            ['type_id', 'required'],
            ['type_id', 'integer'],
            ['type_id', 'in', 'range' => array_keys(self::typeArray())],
            
            ['paymentsystem_id', 'required'],
            ['paymentsystem_id', 'default', 'value' => self::PAYMENT_LOCAL],
            ['paymentsystem_id', 'integer'],
            ['paymentsystem_id', 'in', 'range' => array_keys($this->widthrawalPaymentSystemsArray)],
            
            [['account_id','description'], 'required'],
            [['account_id','updated_at'], 'integer'],
            
            [['description', 'ps_tr_id'], 'string', 'max' => 256],

            ['is_hidden', 'required'],
            ['is_hidden', 'in', 'range' =>  array_keys(self::hiddenArray())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => 'ID',
            'account_id' => 'ID',
            'type_id' => Yii::t('app','Тип операции'),
            'amount' => Yii::t('app','Сумма'),
            'created_at' => Yii::t('app','Дата создания'),
            'updated_at' => Yii::t('app','Дата редактирования'),
            'description' => Yii::t('app','Описание'),
            'ps_tr_id' => '',
            'paymentsystem_id' => Yii::t('app','Платежная система'),
            'status' => Yii::t('app','Статус'),
            'is_hidden'=>Yii::t('app','Статус скрытия')
        ]);
    }

     public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if($this->scenario == self::SCENARIO_MODER_UPDATE){
                $this->created_at = strtotime($this->created_at);
            }
            if(empty($this->created_at)){
                $this->created_at = time();
            }
            return true;
        }
        $this->updated_at =time();
        return false;
    }
    
    public function scenarios(){
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_DEPOSIT => ['paymentsystem_id','amount'],//,'account_id','description','type_id','status'],
            self::TYPE_DEPOSIT_PS => ['amount'],
            self::SCENARIO_WITHDRAWAL => ['paymentsystem_id','amount','account_id','description','type_id','status'], 
            self::SCENARIO_APPROVE => ['status'],
            self::SCENARIO_MODER_UPDATE =>['paymentsystem_id','amount','status','description','created_at','amount','type_id'],
            self::SCENARIO_CHANGE_HIDDEN=>['is_hidden']
        ]);
    }
    
  
    
    /*-------------------------------GETTERS----------------------------------*/
    protected function getStatusHumor(){
        switch ($this->status){
            case self::STATUS_CREATED: return 'label-warning';
            case self::STATUS_SUCCESS: return 'label-success';
            case self::STATUS_REJECT: return 'label-danger';
            default :return 'label-info';    
        }
    }
    
    public function getType_name(){
        $statuses = self::typeArray();
        return isset($statuses[$this->type_id]) ? $statuses[$this->type_id] : '';
    }
    
    public function getStatus_name()
    {
        $statuses = self::statusArray();
        if(in_array($this->status, array_keys($statuses)))
            return '<p class="has-tooltip label '.$this->statushumor.'" title="'.$statuses[$this->status].'">'.$statuses[$this->status].'</p>';       
        return '<p class="has-tooltip label label-warning" title="Ошибка">Ошибка</p>';
    }
    
    public function getPayment_system_name()
    {
        $statuses = self::paymentSystemArray();
        return isset($statuses[$this->paymentsystem_id]) ? $statuses[$this->paymentsystem_id] : '';
    }

    public function getHidden_name()
    {
        $statuses = self::hiddenArray();
        return isset($statuses[$this->is_hidden]) ? $statuses[$this->is_hidden] : '';
    }
    /*-------------------------------GETTERS----------------------------------*/
    
    /*--------------------------INITIALING ARRAYS-----------------------------*/

    public static function hiddenArray()
    {
        return [
            0 => Yii::t('app','Открыта'),
            1 => Yii::t('app','Скрыта'),
        ];
    }
    
    public static function typeArray()
    {
        return [
            self::TYPE_DEPOSIT => Yii::t('app','Пополнение'),
            self::TYPE_WITHDRAWAL => Yii::t('app','Вывод'),
            self::TYPE_INTERNAL_WITHDRAWAL=> Yii::t('app','Внутреннее списание'),
            self::TYPE_INTERNAL_DEPOSIT=> Yii::t('app','Внутреннее начисление'),
            self::TYPE_DEPOSIT_HAND=> Yii::t('app','Системное зачисление'),
            self::TYPE_WITHDRAWAL_HAND=> Yii::t('app','Системное списание'),
        ];
    }
    

    public static function statusArray(){
        return [
            self::STATUS_CREATED => Yii::t('app','Создана'),
            self::STATUS_SUCCESS => Yii::t('app','Проведена'),
            self::STATUS_REJECT => Yii::t('app','Отклонена'),
            self::STATUS_CANCELED => Yii::t('app','Отменена'),
            self::STATUS_MODER => Yii::t('app','На модерации'),
        ];
    }
    
    public static function moderPaySystemArr(){
        return[
            self::PAYMENT_PERFECT_MONEY=>'PerfectMoney',
            self::PAYMENT_ADVANCED_CASH=>'AdvancedCash',
            self::PAYMENT_BTC=>'BTC',
            self::PAYMENT_LOCAL => 'Local',
        ];
       
    }


    public static function getPaymentSystemsArray()
    {
        return 
            [
//                self::PAYMENT_LOCAL => Yii::t('app-account', 'USER_TRANSACTION_PAYMENT_LOCAL'),
//                self::PAYMENT_WMR=>'WMR',
//                self::PAYMENT_WMZ=>'WMZ',
                self::PAYMENT_PERFECT_MONEY=>'PerfectMoney',
                self::PAYMENT_ADVANCED_CASH=>'AdvancedCash',
                self::PAYMENT_BTC=>'BTC',
//                self::PAYMENT_YANDEX_MONEY=>'YandexMoney',
//                self::PAYMENT_QIWI=>'QIWI',
//                self::PAYMENT_VISA_OTHERS =>'Visa',
//                self::PAYMENT_MASTER_CARD_OTHERS=>'MasterCard',
//                self::PAYMENT_INTERKASSA=>'Interkassa'
            ];
    }

     public  function getWidthrawalPaymentSystemsArray()
    {
        return 
            [
                self::PAYMENT_LOCAL => 'Local',
                self::PAYMENT_WMR=>'WMR',
                self::PAYMENT_WMZ=>'WMZ',
                self::PAYMENT_PERFECT_MONEY=>'PerfectMoney',
                self::PAYMENT_ADVANCED_CASH=>'AdvancedCash',
                self::PAYMENT_YANDEX_MONEY=>'YandexMoney',
                self::PAYMENT_QIWI=>'QIWI',
                self::PAYMENT_VISA_OTHERS =>'Visa',
                self::PAYMENT_MASTER_CARD_OTHERS=>'MasterCard',
                self::PAYMENT_INTERKASSA=>'Interkassa',
                self::PAYMENT_BTC=>'BTC'
            ];
    }
    public static function paymentSystemArray(){
        return [
                // self::PAYMENT_LOCAL => 'Системная',
                // self::PAYMENT_WMR=>'WMR',
                // self::PAYMENT_WMZ=>'WMZ',
                self::PAYMENT_PERFECT_MONEY=>'PerfectMoney',
                self::PAYMENT_ADVANCED_CASH=>'AdvancedCash',
                self::PAYMENT_BTC=>'BTC',
                // self::PAYMENT_YANDEX_MONEY=>'YandexMoney',
                // self::PAYMENT_QIWI=>'QIWI',
                // self::PAYMENT_VISA_OTHERS =>'Visa',
                // self::PAYMENT_MASTER_CARD_OTHERS=>'MasterCard',
                // self::PAYMENT_INTERKASSA=>'Interkassa'
            ];
    }
    protected function getPaymentSystem()
    {
        switch($this->paymentsystem_id)
        {
            case self::PAYMENT_PERFECT_MONEY: return 'PerfectMoney';
            case self::PAYMENT_ADVANCED_CASH: return 'AdvancedCash';
            case self::PAYMENT_BTC: return 'BTC';
            default : return 'Не выбрана';
        }
    }
    /*--------------------------INITIALING ARRAYS-----------------------------*/
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'account_id'));
    }
    
    public function getAccount(){
        return $this->hasOne(Useraccount::className(), array('user_id' => 'account_id'));
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    
    public function changeBalance(){
        if($this->status == self::STATUS_SUCCESS){
            $account = $this->account;
	    $beforeBalance = $account->balance;
            if($this->type_id == self::TYPE_DEPOSIT)
                $account->balance = $account->balance + $this->amount;
            elseif($this->type_id == self::TYPE_WITHDRAWAL)
                $account->balance = $account->balance - $this->amount;
            $account->balance = (float)number_format($account->balance, 2, ".","");
            if($account->save())
                $this->balanceTransaction($beforeBalance,$account->balance);
        }    
    }
    
    public function balanceTransaction($beforeBalance,$afterBalance) {
        $changebalance =  new Userchangebalance();
        $changebalance->account_id = $this->account_id;
        $changebalance->usertransaction_id = $this->id;
        $changebalance->before_balance = $beforeBalance;
        $changebalance->after_balance = $afterBalance;
        $changebalance->type_id = $this->type_id;
        $changebalance->amount = $this->amount;
        $changebalance->save();
    }    
    public function depositAccount()
    {
        if($this->status == self::STATUS_SUCCESS and $this->type_id == self::TYPE_DEPOSIT)
        {
            $this->account->balance =  $this->account->balance + $this->amount;
            if($this->account->save()) 
            {
//                $changeBalance = new Useraccount();
//                $changeBalance->balanceTransaction($beforeBalance,$this->account->balance,$this,Changebalance::TYPE_DIPOSIT);
                return true;
            }
        }
        return false;
    }
    public function makeDataForAdv()
    {
        $this->adv_acc = Yii::$app->params['advancedcash_email'];
        $this->adv_name = Yii::$app->params['advancedcash_sci_name'];
        $this->adv_currency = 'USD';
        $this->adv_amount = $this->amount;
        $this->adv_orderid = $this->id;
        $this->adv_hash = hash('sha256',$this->adv_acc.':'.$this->adv_name.':'.$this->amount.':'.$this->adv_currency.':'.Yii::$app->params['advancedcash_key'].':'.$this->adv_orderid);
        return true;
    }
    public function makeDataForPM()
    {
        $this->pf_acc = Yii::$app->params['pf_acc'];
        $this->pf_name = Yii::$app->params['systemName'];
        $this->pf_units = 'USD';
        $this->pf_status = 'https://sealines.company/finance/request/paypm';
        $this->pf_pay = 'https://sealines.company/site/payreturn?ps=perfectmoney&result=ok';
        $this->pf_paymeth = 'POST';
        $this->pf_nopay = 'https://sealines.company/site/payreturn?ps=perfectmoney&result=error';
        $this->pf_nopaymeth = 'POST';
        $this->pf_amount = $this->amount;
        $this->pf_hash = strtoupper(md5($this->id.':'.$this->pf_acc.':'.$this->pf_amount.':'.$this->pf_units.':NULL:NULL:'.Yii::$app->params['pf_secret_key'].':'.time()));
        return true;
    }
    public function makeDataForBTC()
    {
        $this->btc_xpub = Yii::$app->params['btc_xpub'];
        $this->btc_secret = Yii::$app->params['btc_secret'];
        $this->btc_api_key = Yii::$app->params['btc_api_key'];
        $this->btc_curs = $this->curs;
        $this->btc_amount = (float)number_format($this->amount/$this->btc_curs, 8, ".","");
        $post = "$this->id:$this->btc_amount:$this->btc_secret";
        
        $my_callback_url = 'https://sealines.company/finance/request/paybtc';
        $my_callback_url .='?tr_id='.$this->id;
        $my_callback_url .='&btc_amount='.$this->btc_amount;
        $my_callback_url .='&hash='. strtoupper(md5($post));
        
        $root_url = 'https://api.blockchain.info/v2/receive';
        $parameters = 'xpub=' .$this->btc_xpub. '&callback=' .urlencode($my_callback_url). '&key=' .$this->btc_api_key;
        
        $str = 'Курс: '.$this->btc_curs.' Транзакция: '.$this->id;
        Yii::info('Blockchain:'.$str.' '.$parameters, 'blockchaincurs');
        
        $response = file_get_contents($root_url . '?' . $parameters);
        
        $object = json_decode($response);
        $this->btc_form = $this->getMessage($object->address, $this->btc_amount);
        
        return true;
    }
    public function getMessage($response, $amount) {
        $text = $response ? '<p style="text-align:center;margin:20px 0;"><a style="font-size:14px;" href="'.'bitcoin:'.$response.'?amount='.$amount.'">'.$response.'</a></p>' : 'Возникла ошибка! Обратитесь в поддержку.';
        if ($response) {
            $qr = new Qrcode();
            $qr->text('bitcoin:'.$response.'?amount='.$amount);
            $text .= "<p style='text-align:center;'><img src='".$qr->get_link(250)."' border='0'/></p>";
        }
        return $text;
    }
    public static function btcCurs() {
        $exchange_query_result = file_get_contents('https://blockchain.info/ru/ticker');
        $exchange_data_obj = json_decode($exchange_query_result);
        if (isset($exchange_data_obj->USD)) {
            return $exchange_data_obj->USD->last;
        }
        else
            return 3700;
    }
}
