<?php

use yii\db\Migration;

/**
 * Handles the creation of table `geo_regions`.
 */
class m170626_084622_create_geo_regions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_geo_regions', [
            'id' => $this->primaryKey(),
            'iso' => $this->string(7)->notNull(),
            'country' => $this->char(2)->notNull(),
            'name_ru' => $this->string(128)->notNull(),
            'name_en' => $this->string(128)->notNull(),
            'timezone' => $this->string(30)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_geo_regions');
    }
}
