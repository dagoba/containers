<?php

namespace app\models;

use Yii;
use app\components\CBRAgent;
use app\models\CursChange;
/**
 * This is the model class for table "tbl_curs".
 *
 * @property int $currency
 * @property string $curs
 */
class Curs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const RUB = 643;
    const USD = 840;
    const EUR = 978;
    
    public static function tableName()
    {
        return 'tbl_curs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency', 'curs'], 'required'],
            [['currency'], 'integer'],
            [['curs'], 'number'],
            [['currency'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency' => 'Currency',
            'curs' => 'Curs',
        ];
    }
    public static function changecurs()
    {
        $cbr = new CBRAgent();
        if ($cbr->load()){
            $models = self::find()->all();
            if($models)
            {
                foreach ($models as $model)
                {
                    $beforecurs = $model->curs;
                    switch ($model->currency) {
                        case self::USD: 
                                $model->curs = $cbr->get('USD');
                        break;
                        case self::EUR: 
                                $model->curs = $cbr->get('EUR');
                        break;
                    }
                    if($model->save())
                    {
                        $change = new CursChange();
                        $change->before = $beforecurs;
                        $change->after = $model->curs;
                        $change->currency = $model->currency;
                        $change->created_at = time();
                        $change->save();
                        echo 111;
                    }
                    else
                        return false;
                }
            }
            return true;
        }
        else
            return false;
    }
    public static function curs($currency)
    {
       return self::findOne($currency)->curs;
    }
}
