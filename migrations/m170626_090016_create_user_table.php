<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170626_090016_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
            'email_confirm_token' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull(),
            'first_name' => $this->string(50),
            'last_name' => $this->string(50),
            'country_id' => $this->integer(),
            'city_id' => $this->string(100),
            'useragent' => $this->string(),
            'user_ip' => $this->string(),
            'status' => $this->smallInteger()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_user');
    }
}
