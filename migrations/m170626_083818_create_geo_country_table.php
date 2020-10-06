<?php

use yii\db\Migration;

/**
 * Handles the creation of table `geo_country`.
 */
class m170626_083818_create_geo_country_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_geo_country', [
            'id' => $this->primaryKey(),
            'iso' => $this->char(2)->notNull(),
            'continent' => $this->char(2)->notNull(),
            'name_ru' => $this->string(128)->notNull(),
            'name_en' => $this->string(128)->notNull(),
            'lat' => $this->decimal(6,2)->notNull(),
            'lon' => $this->decimal(6,2)->notNull(),
            'timezone' => $this->string(30)->notNull(),
            'group_id' => $this->integer()->defaultValue(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_geo_country');
    }
}
