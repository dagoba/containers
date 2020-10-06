<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_change_profile`.
 */
class m170626_094910_create_user_change_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user_change_profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'user_agent' => $this->string()->notNull(),
            'user_ip' => $this->string()->notNull(),
            'before' => $this->text()->notNull(),
            'after' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_user_change_profile');
    }
}
