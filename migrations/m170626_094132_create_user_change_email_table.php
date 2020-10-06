<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_change_email`.
 */
class m170626_094132_create_user_change_email_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user_change_email', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'before_email' => $this->string()->notNull(),
            'after_email' => $this->string()->notNull(),
            'reset_token' => $this->string()->notNull(),
            'created_user_ip' => $this->string()->notNull(),
            'confirm_user_ip' => $this->string(),
            'created_user_agent' => $this->string(),
            'confirm_user_agent' => $this->string(),
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
        $this->dropTable('tbl_user_change_email');
    }
}
