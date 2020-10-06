<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_item_child`.
 */
class m170626_082326_create_auth_item_child_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('tbl_auth_item_child', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('tbl_auth_item_child');
    }
}
