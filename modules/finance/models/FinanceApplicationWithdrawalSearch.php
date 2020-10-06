<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class FinanceApplicationWithdrawalSearch extends FinanceApplicationWithdrawal{

    public $date_from;
    public $date_to;
    
    public function rules(){
        return 
        [
            [['account_id','id'], 'integer'],
            
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::statusSearchFullArray())],

            ['paymentsystem_id', 'integer'],
            ['paymentsystem_id', 'in', 'range' => array_keys(self::paymentSystemsArray())],
            
            [['date_from', 'date_to'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributeLabels(){
        return ArrayHelper::merge(parent::attributeLabels(), [
            'date_from' => 'Дата с',
            'date_to' => 'Дата по',
        ]);
    }
    
    public function userSearch($params){
        $query = self::find()
            ->where(['account_id'=>Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $this->load($params);
        if (!$this->validate())
            return $dataProvider;
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'paymentsystem_id' => $this->paymentsystem_id,
        ]);
       
        $query
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);
        return $dataProvider;
    }
    
    public function moderSearch($params){
        $query = self::find();
           // ->where(['account_id'=>Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $this->load($params);
        if (!$this->validate())
            return $dataProvider;
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'paymentsystem_id' => $this->paymentsystem_id,
        ]);
       
        $query
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);
        return $dataProvider;
    }
    
    

    public static function statusSearchFullArray(){
        return [
            self::STATUS_CREATED =>'Ожидает оплаты',
            self::STATUS_PRE_CREATED =>'Ожидает подтверждения',
            self::STATUS_SUCCESS => 'Проведена',
            self::STATUS_REJECT => 'Отклонена',
        ];
    }
}
