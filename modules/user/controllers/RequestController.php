<?php

namespace app\modules\user\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use app\modules\user\models\Usertransaction;

class RequestController extends Controller
{
  
    public function beforeAction($action) 
    {
        $this->enableCsrfValidation = FALSE;
        return parent::beforeAction($action);
    }
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
    public function actionAdvancedcash()
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
            
            $hash = hash('sha256',$_POST['ac_account_email'].':'.$_POST['ac_sci_name'].':'.$_POST['ac_amount'].':'.$_POST['ac_currency'].':'.Yii::$app->params['advancedcash_key'].':'.$_POST['ac_order_id']);
            
            $str = 'Пришло '.$_POST['ac_hash'].' Сформировали '.$hash;
            Yii::info($str, $paysystem);
            if($hash!=$_POST['ac_hash']){
                Yii::info('Сигнатура неверна.Детали:'.$str, $paysystem);
                exit;
            }
            else{
                $transaction = Usertransaction::model()->findByPk($_POST['ac_order_id']);
                if($transaction and $transaction->status==Usertransaction::STATUS_CREATED)
                {
                    $transaction->ps_tr_id = $_POST['PAYMENT_BATCH_NUM'];
                    if($_POST['ac_amount'] <> $transaction->amount)
                        $transaction->status = Usertransaction::STATUS_MODER;
                    else
                        $transaction->status = Usertransaction::STATUS_SUCCESS;
                    if($transaction->save())
                    {
                        Yii::info('Транзакцию сохранили.',$paysystem);
                        if($transaction->depositAccount())
                            Yii::info($transaction->id. ", Увеличили счёт. ".$transaction->account_id, $paysystem);
                        else
                            Yii::info('Не удалось увеличить баланс', $paysystem);
                    }
                }
            }
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
                        if($transaction->depositAccount())
                            Yii::info($transaction->id. ", Увеличили счёт. ".$transaction->account_id, $paysystem);
                        else
                            Yii::info('Не удалось увеличить баланс', $paysystem);
                    }
                }
            }
        }
    }
}
