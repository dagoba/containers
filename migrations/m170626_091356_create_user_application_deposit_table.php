<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_application_deposit`.
 */
class m170626_091356_create_user_application_deposit_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user_application_deposit', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10,2)->notNull(),
            'usercomment' => $this->string(),
            'modercomment' => $this->string(),
            'paymentsystem_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
            'usertransaction_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_user_application_deposit');
    }
}
