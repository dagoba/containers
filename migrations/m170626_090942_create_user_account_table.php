<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_account`.
 */
class m170626_090942_create_user_account_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_user_account', [
            'user_id' => $this->integer()->notNull(),
            'balance' => $this->decimal(13,2)->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_user_account');
    }
}
