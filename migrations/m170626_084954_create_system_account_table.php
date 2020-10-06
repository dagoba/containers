<?php

use yii\db\Migration;

/**
 * Handles the creation of table `system_account`.
 */
class m170626_084954_create_system_account_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_system_account', [
            'id' => $this->primaryKey(),
            'balance' => $this->decimal(13,4)->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_system_account');
    }
}
