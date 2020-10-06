<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_rule`.
 */
class m170626_082609_create_auth_rule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_auth_rule', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_auth_rule');
    }
}
