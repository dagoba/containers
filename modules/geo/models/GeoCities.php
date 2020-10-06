<?php

namespace app\modules\geo\models;

use Yii;
use app\modules\geo\models\GeoRegions;
use app\modules\geo\models\GeoCountry;

/**
 * This is the model class for table "{{%geo_cities}}".
 *
 * @property integer $id
 * @property integer $region_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $lat
 * @property string $lon
 */
class GeoCities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_cities}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_id', 'name_ru', 'name_en', 'lat', 'lon'], 'required'],
            [['id', 'region_id'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['name_ru', 'name_en'], 'string', 'max' => 128],
        ];
    }

    /*------------------------------RELATIONS---------------------------------*/
    public function getCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['iso' => 'country'])
            ->viaTable(GeoRegions::tableName(), ['id' => 'region_id']);
    }
    
    public function getRegion()
    {
        return $this->hasOne(GeoRegions::className(), ['id' => 'region_id']);
    }
    /*------------------------------RELATIONS---------------------------------*/
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_id' => 'Region ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'lat' => 'Lat',
            'lon' => 'Lon',
        ];
    }
}
