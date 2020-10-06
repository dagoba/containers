<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UserSearch extends User
{
    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            
            [['status'], 'integer'],
            ['status', 'in', 'range' =>  array_keys(self::getStatusesArray())],

            ['email', 'safe'],
            ['email', 'email'],
            
            ['username', 'match', 'pattern' => '/^[а-яА-ЯєЄіІёЁa-zA-Z-0-9-_\. ]+$/u'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'safe'],
            
            [['date_from', 'date_to'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    public function scenarios(){
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
                
                ->where(['status'=>self::STATUS_ACTIVE]);
                //->with(['userpass']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);
        $this->load($params);
        if (!$this->validate()){
            return $dataProvider;
        }
        $query
            ->andFilterWhere(['id' => $this->id,'status' => $this->status])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime(date('Y-m-d 00:00:00', strtotime($this->date_from))) : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime(date('Y-m-d 23:59:59', strtotime($this->date_to))) : null]);
        return $dataProvider;
    }
}
