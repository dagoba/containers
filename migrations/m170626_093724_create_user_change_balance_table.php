<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_change_balance`.
 */
class m170626_093724_create_user_change_balance_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user_change_balance', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->notNull(),
            'usertransaction_id' => $this->integer()->notNull(),
            'before_balance' => $this->float(13,2)->notNull(),
            'after_balance' => $this->float(13,2)->notNull(),
            'amount' => $this->float(13,2)->notNull(),
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
        $this->dropTable('tbl_user_change_balance');
    }
}
