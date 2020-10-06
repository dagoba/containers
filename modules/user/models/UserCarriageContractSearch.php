<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\user\models\UserCarriageContract;

class UserCarriageContractSearch extends UserCarriageContract
{

    public $date_from;
    public $date_to;
    
    public function rules()
    {
        return 
        [
            
            [['id','user_id','creator_id'], 'integer'],

            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys($this->statusArray())],

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

    public function moderSearch($params){
        $query = self::find()
                 ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()){
            return $dataProvider;
        }
        $query->andFilterWhere([
            'status' => $this->status,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'creator_id' => $this->creator_id,
        ])
        ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
        ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);
        return $dataProvider;
    }
    public function userSearch($params){
        $query = self::find()->where(['user_id'=>Yii::$app->user->id])
                 ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()){
            return $dataProvider;
        }
        $query->andFilterWhere([
            'status' => $this->status,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'creator_id' => $this->creator_id,
        ])
        ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
        ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);
        return $dataProvider;
    }
}
