<?php

namespace app\modules\geo\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\geo\models\GeoRegions;
use app\modules\geo\models\GeoCities;

/**
 * This is the model class for table "{{%geo_country}}".
 *
 * @property integer $id
 * @property string $iso
 * @property string $continent
 * @property string $name_ru
 * @property string $name_en
 * @property string $lat
 * @property string $lon
 * @property string $timezone
 */
class GeoCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'iso', 'continent', 'name_ru', 'name_en', 'lat', 'lon', 'timezone'], 'required'],
            [['id'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['iso', 'continent'], 'string', 'max' => 2],
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
            'continent' => 'Continent',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'timezone' => 'Timezone',
        ];
    }
    
    /*------------------------------RELATIONS---------------------------------*/
    public function getCities()
    {
        return $this->hasMany(GeoCities::className(), ['region_id' => 'id'])
            ->viaTable(GeoRegions::tableName(), ['country' => 'iso'])
            ->orderBy(['name_ru' => SORT_ASC]);
    }
    
    public function getRegions()
    {
        return $this->hasMany(GeoRegions::className(), ['country' => 'iso'])
               ->orderBy(['name_ru' => SORT_ASC]); 
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    public static function dropDownList()
    {
        $countries = self::find()
            ->orderBy(['name_ru'=>SORT_ASC])
            ->all();
        $items = ArrayHelper::map($countries,'id','name_ru');
        return $items;
    }
}
