<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_transaction`.
 */
class m170626_095211_create_user_transaction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user_transaction', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->notNull(),
            'type_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(13,2)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'description' => $this->string()->notNull(),
            'ps_tr_id' => $this->string(),
            'paymentsystem_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_user_transaction');
    }
}
