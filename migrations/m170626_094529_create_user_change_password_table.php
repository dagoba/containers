<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_change_password`.
 */
class m170626_094529_create_user_change_password_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user_change_password', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'user_ip' => $this->string()->notNull(),
            'useragent' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_user_change_password');
    }
}
