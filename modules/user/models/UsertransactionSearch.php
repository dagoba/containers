<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UsertransactionSearch extends Usertransaction
{
    public $useremail;
    
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['id', 'status','type_id','paymentsystem_id'], 'integer'],
            
            [['amount'], 'number'],
            
            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(self::statusArray())],
            
            ['type_id', 'integer'],
            ['type_id', 'in', 'range' => array_keys(self::typeArray())],

            ['paymentsystem_id', 'integer'],
            ['paymentsystem_id', 'in', 'range' => array_keys(self::paymentSystemArray())],
            
            
            [['description','ps_tr_id'], 'safe'],
            
            [['date_from', 'date_to'], 'date', 'format' => 'php:d.m.Y'],

            ['is_hidden', 'integer'],
            ['is_hidden', 'in', 'range' =>  array_keys(self::hiddenArray())],
        ];
    }

    public function scenarios(){
        return Model::scenarios();
    }

    public function attributeLabels(){
        return ArrayHelper::merge(parent::attributeLabels(), [
            'date_from' => 'Дата с',
            'date_to' => 'Дата по',
        ]);
    }

    public function userSearch($params){
        $query = Usertransaction::find()->where([
            'account_id'=>Yii::$app->user->identity->id,
            'is_hidden'=>0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query
            ->andFilterWhere([
                'id' => $this->id,
                'type_id' => $this->type_id,
                'paymentsystem_id' => $this->paymentsystem_id,
                'amount' => $this->amount,
                'status' => $this->status,
            ])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);

        return $dataProvider;
    }
    
    public function moderSearch($params){
        $query = Usertransaction::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);
        $this->load($params);
        if (!$this->validate())
            return $dataProvider;
        $query
            ->andFilterWhere([
                'id' => $this->id,
                'type_id' => $this->type_id,
                'paymentsystem_id' => $this->paymentsystem_id,
                'amount' => $this->amount,
                'status' => $this->status,
                'is_hidden' => $this->is_hidden,
            ])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);

        return $dataProvider;
    }
    
    public function getStatusSearchArray(){
        return [
            self::STATUS_MODER => 'На модерации',
            self::STATUS_SUCCESS => 'Проведена',
            self::STATUS_REJECT => 'Отклонена'
        ];
    }
}
