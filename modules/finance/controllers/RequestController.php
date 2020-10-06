<?php

namespace app\modules\finance\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use app\modules\user\models\Usertransaction;
use app\modules\user\models\Useraccount;
use app\models\Bitcoin;

class RequestController extends Controller
{
    public $layout = '/payment';
    public function actionInterkassa()
    {      
        
        Yii::info('Запрос на интеркассу', 'interkassa');
        if(isset($_POST['ik_co_id']))
        {
            $interkassa=new Interkassa();
            $interkassa->responseProcessing($_POST);
        }
        else
        {
            Yii::info('Пост не пришёл', 'interkassa');
            Yii::info('Завершили обработку', 'interkassa');
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        Yii::info('Завершили обработку', 'interkassa');
    }
    public function actionPayadv()
    {
        $paysystem = 'advancedcash';
        Yii::info('Запрос на AdvancedCash', $paysystem);
        $str = 'Пришло ';
        if(isset($_POST))
        {
            foreach($_POST as $key => $value) {
              $str .= " | ".$key." : ".$value;
            }
            Yii::info('Дёрнули AdvancedAction', $paysystem);
            Yii::info($str, $paysystem);

            $ac_transfer = $_POST['ac_transfer'];
            $ac_start_date = $_POST['ac_start_date'];
            $ac_sci_name = $_POST['ac_sci_name'];
            $ac_src_wallet = $_POST['ac_src_wallet'];
            $ac_dest_wallet = $_POST['ac_dest_wallet'];
            $ac_order_id = $_POST['ac_order_id'];
            $ac_amount = $_POST['ac_amount'];
            $ac_merchant_currency = $_POST['ac_merchant_currency'];
            $ac_hash = $_POST['ac_hash'];
            $ac_code = Yii::$app->params['advancedcash_key'];
            
            $post = "$ac_transfer:$ac_start_date:$ac_sci_name:$ac_src_wallet:$ac_dest_wallet:$ac_order_id:$ac_amount:$ac_merchant_currency:$ac_code";
        
            $hash = hash('sha256', $post);

            $str = 'Пришло '.$ac_hash.' Сформировали '.$hash;
            Yii::info($str, $paysystem);
            if($hash!=$ac_hash){
                Yii::info('Сигнатура неверна.Детали:'.$str, $paysystem);
                exit;
            }
            else{
            	$transaction = Usertransaction::find()->where(['id'=>(int)$_POST['ac_order_id']])->one();
                if($transaction != NULL and $transaction->status==Usertransaction::STATUS_CREATED)
                {
                    
                    if($_POST['ac_amount'] <> $transaction->amount)
                        $transaction->status = Usertransaction::STATUS_MODER;
                    else
                        $transaction->status = Usertransaction::STATUS_SUCCESS;
                    if($transaction->save(false))
                    {
                        Yii::info('Транзакцию сохранили.',$paysystem);
                        $beforebalance = $transaction->account->balance;
                        $transaction->account->balance += $transaction->amount;
                        Useraccount::updateAll(['balance' => $transaction->account->balance ], ['user_id' => $transaction->account_id]);
                        if($beforebalance < $transaction->account->balance)
                            Yii::info($transaction->id. ", Увеличили счёт. ".$transaction->account_id, $paysystem);
                        else
                            Yii::info('Не удалось увеличить баланс', $paysystem);
                    }
                }
            }
        }
    }
    public function actionPaybtc()
    {
        $paysystem = 'blockchain';
        Yii::info('Запрос на blockchain test', $paysystem);
        $secret = Yii::$app->params['btc_secret'];
        $btc_tr_id = $_GET['tr_id'];
        $btc_amaunt = $_GET['btc_amount'];
        $btc_hash = $_GET['hash'];
        $value = $_GET['value'];
        
        $btc_transaction_hash = $_GET['transaction_hash'];
        $btc_address = $_GET['address'];
        $btc_confirmations = $_GET['confirmations'];

        $post = "$btc_tr_id:$btc_amaunt:$secret";
        $hash = strtoupper(md5($post));
        
        $str = "Пришло: $btc_hash Сформировали: $hash || Сумма в сатошей: $value";
        if($btc_hash != $hash)
        {
            Yii::info('Сигнатура неверна.Детали:'.$str, $paysystem);
            exit;
        }
        Yii::info('Yakas ebala', $paysystem);
        $bitcoin = Bitcoin::find()->where(['tr_id'=>(int)$btc_tr_id])->one();
        


        $confirm = 0;
        
        $currency = "USD";
        $exchange_query_result = file_get_contents('https://blockchain.info/ru/ticker');
        $exchange_data_obj = json_decode($exchange_query_result);
        
        $params = [
            'id' => $btc_tr_id,
            'transaction_hash' => $btc_transaction_hash,
            'address' => $btc_address,
            'confirmations' => $btc_confirmations,
            'value' => $value,
            'value_pc' => $exchange_data_obj->$currency->last,
        ];

        if ($bitcoin === null) {
            
            if (Bitcoin::createConfirm($params)) {
                Yii::info('Подтверждение по транзакции: '.$btc_tr_id.' Создано', $paysystem);
            } else {
                Yii::info('Ошибка создания подтверждения по транзакции: '.$btc_tr_id, $paysystem);
            }
        } else {
            if ($confirm = $bitcoin->confirmation($params)) {
                Yii::info('Подтверждение по транзакции: '.$btc_tr_id.' Сохранено', $paysystem);
            } else {
                Yii::info('Ошибка создания подтверждение по транзакции: '.$btc_tr_id, $paysystem);
            }
        }
        
        if ($btc_confirmations < 3) {
            return false;
        } else {
            echo '*ok*';
            Yii::info('*ok*'.$btc_tr_id, $paysystem);
        }
        
        $transaction = Usertransaction::find()->where(['id'=>(int)$btc_tr_id])->one();

        if ($transaction === null) {
            Yii::info('Транзакция не найдена: '.$btc_tr_id, $paysystem);
            return false;
        }
        if ($transaction->status != Usertransaction::STATUS_CREATED) {
            Yii::info('Ошибка статуса: '.$btc_tr_id, $paysystem);
            return false;
        }

        if (($value / 100000000) < $btc_amaunt) {
            Yii::info('Ошибка платежа транзакция на модерации: '.$btc_tr_id, $paysystem);
            $transaction->status = Usertransaction::STATUS_MODER;
            $transaction->save();
            return false;
        }
        else
            $transaction->status = Usertransaction::STATUS_SUCCESS;
        if($transaction->save())
        {
            Yii::info('Транзакцию сохранили.',$paysystem);
            $beforebalance = $transaction->account->balance;
            $transaction->account->balance += $transaction->amount;
            Useraccount::updateAll(['balance' => $transaction->account->balance ], ['user_id' => $transaction->account_id]);
            if($beforebalance < $transaction->account->balance)
                Yii::info($transaction->id. ", Увеличили счёт. ".$transaction->account_id, $paysystem);
            else
                Yii::info('Не удалось увеличить баланс', $paysystem);
        }
    }
    public function actionPaypm()
    {
        $paysystem = 'perfectmoney';
        Yii::info('Запрос на PerfectMoney', $paysystem);
        $str = 'Пришло ';
        if(isset($_POST))
        {
            foreach($_POST as $key => $value) {
              $str .= " | ".$key." : ".$value;
            }
            Yii::info('Дёрнули PerfectAction', $paysystem);
            Yii::info($str, $paysystem);
            $str = 
                    $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
                    $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
                    $_POST['PAYMENT_BATCH_NUM'].':'.
                    $_POST['PAYER_ACCOUNT'].':'.strtoupper(md5(Yii::$app->params['pf_secret_key'])).':'.
                    $_POST['TIMESTAMPGMT'];
            $hash=strtoupper(md5($str));
            $str = 'Пришло '.$_POST['V2_HASH'].' Сформировали '.$hash;
            Yii::info($str, $paysystem);
            if($hash!=$_POST['V2_HASH']){
                Yii::info('Сигнатура неверна.Детали:'.$str, $paysystem);
                exit;
            }
            else{
                $transaction = Usertransaction::find()->where(['id'=>(int)$_POST['PAYMENT_ID']])->one();
                if($transaction != NULL and $transaction->status==Usertransaction::STATUS_CREATED)
                {
                    $transaction->ps_tr_id = $_POST['PAYMENT_BATCH_NUM'];
                    if($_POST['PAYMENT_AMOUNT'] <> $transaction->amount)
                        $transaction->status = Usertransaction::STATUS_MODER;
                    else
                        $transaction->status = Usertransaction::STATUS_SUCCESS;
                    if($transaction->save())
                    {
                        Yii::info('Транзакцию сохранили.',$paysystem);
                        $beforebalance = $transaction->account->balance;
                        $transaction->account->balance += $transaction->amount;
                        Useraccount::updateAll(['balance' => $transaction->account->balance ], ['user_id' => $transaction->account_id]);
                        if($beforebalance < $transaction->account->balance)
                            Yii::info($transaction->id. ", Увеличили счёт. ".$transaction->amount, $paysystem);
                        else
                            Yii::info('Не удалось увеличить баланс', $paysystem);
                    }
                }
            }
        }
    }
}
