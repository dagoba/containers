<?php

use yii\db\Migration;

/**
 * Handles the creation of table `system_change_balance`.
 */
class m170626_085220_create_system_change_balance_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_system_change_balance', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->integer()->notNull(),
            'before_balance' => $this->float(13,4)->notNull(),
            'after_balance' => $this->float(13,4)->notNull(),
            'amount' => $this->float(13,4)->notNull(),
            'type_id' => $this->integer()->notNull(),
            'account_ip' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_system_change_balance');
    }
}
