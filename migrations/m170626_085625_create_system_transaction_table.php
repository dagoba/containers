<?php

use yii\db\Migration;

/**
 * Handles the creation of table `system_transaction`.
 */
class m170626_085625_create_system_transaction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_system_transaction', [
            'id' => $this->primaryKey(),
            'campaignaccount_id' => $this->integer(),
            'platformaccount_id' => $this->integer(),
            'type_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(13,4)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'description' => $this->string()->notNull(),
            'status' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_system_transaction');
    }
}
