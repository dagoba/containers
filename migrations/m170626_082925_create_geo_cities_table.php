<?php

use yii\db\Migration;

/**
 * Handles the creation of table `geo_cities`.
 */
class m170626_082925_create_geo_cities_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_geo_cities', [
            'id' => $this->primaryKey(),
            'region_id' => $this->integer(8)->notNull(),
            'name_ru' => $this->string(128)->notNull(),
            'name_en' => $this->string(128)->notNull(),
            'lat' => $this->decimal(10,5)->notNull(),
            'lon' => $this->decimal(10,5)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_geo_cities');
    }
}
