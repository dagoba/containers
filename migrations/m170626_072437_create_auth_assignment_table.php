<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_assignment`.
 */
class m170626_072437_create_auth_assignment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_auth_assignment', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_auth_assignment');
    }
}
