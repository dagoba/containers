<?php

namespace app\modules\geo\models;

use Yii;
use app\modules\geo\models\GeoCountry;
use app\modules\geo\models\GeoCities;

/**
 * This is the model class for table "{{%geo_regions}}".
 *
 * @property integer $id
 * @property string $iso
 * @property string $country
 * @property string $name_ru
 * @property string $name_en
 * @property string $timezone
 */
class GeoRegions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_regions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'iso', 'country', 'name_ru', 'name_en', 'timezone'], 'required'],
            [['id'], 'integer'],
            [['iso'], 'string', 'max' => 7],
            [['country'], 'string', 'max' => 2],
            [['name_ru', 'name_en'], 'string', 'max' => 128],
            [['timezone'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iso' => 'Iso',
            'country' => 'Country',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'timezone' => 'Timezone',
        ];
    }
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['iso' => 'country']);
    }
    
    public function getCities()
    {
        return $this->hasMany(GeoCities::className(), ['region_id' => 'id'])
            ->orderBy(['name_ru' => SORT_ASC]);
    }
    /*------------------------------RELATIONS---------------------------------*/
}
