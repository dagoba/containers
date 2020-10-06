<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\user\models\ApplicationHandDeposit;

class ApplicationHandDepositSearch extends ApplicationHandDeposit
{

    public $date_from;
    public $date_to;
    
    public function rules()
    {
        return 
        [
            
            ['account_id', 'integer'],
            
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::getStatusesSearchFullArray())],

            ['paymentsystem_id', 'integer'],
            ['paymentsystem_id', 'in', 'range' => array_keys(self::getPaymentSystemsArray())],
            
            [['date_from', 'date_to'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'date_from' => 'Дата с',
            'date_to' => 'Дата по',
        ]);
    }

    public function searchOld($params)
    {
        $query = self::find()
                 ->where(['!=','status',self::STATUS_CREATED]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

       $query->andFilterWhere([
            'account_id' => $this->account_id,
            'status' => $this->status,
            'paymentsystem_id' => $this->paymentsystem_id,
        ]);
       
        $query
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);
        return $dataProvider;
    }
    
    public function searchNew($params)
    {
        $query = self::find()
                 ->where(['status'=>self::STATUS_CREATED]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);

       return $dataProvider;
    }
    
    public function searchByUser($params,$id)
    {
        $query = self::find()
                 ->where(['account_id'=>intval($id)]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

       $query->andFilterWhere([
            'status' => $this->status,
            'paymentsystem_id' => $this->paymentsystem_id,
        ]);
       
        $query
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);
        return $dataProvider;
    }
    
    public static function getStatusesSearchArray()
    {
        return 
        [
            self::STATUS_SUCCESS => 'Проведена',
            self::STATUS_REJECT => 'Отклонена',
        ];
    }
    
     public static function getStatusesSearchFullArray()
    {
        return 
        [
            self::STATUS_CREATED =>'Ожидает оплаты',
            self::STATUS_SUCCESS => 'Проведена',
            self::STATUS_REJECT => 'Отклонена',
        ];
    }
   
}
