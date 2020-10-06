<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_item`.
 */
class m170626_081801_create_auth_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('tbl_auth_item', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->insert('tbl_auth_item',[
            'name' => 'admin',
            'type' => 1,
            'description' => 'Администратор',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => 1439966829,
            'updated_at' => 1439966829,
        ]);
        $this->insert('tbl_auth_item',[
            'name' => 'guest',
            'type' => 1,
            'description' => 'Гость',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => 1439966829,
            'updated_at' => 1439966829,
        ]);
        $this->insert('tbl_auth_item',[
            'name' => 'isOwnProfile',
            'type' => 2,
            'description' => 'Проверка профиля',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => 1439966829,
            'updated_at' => 1439966829,
        ]);
        $this->insert('tbl_auth_item',[
            'name' => 'moder',
            'type' => 1,
            'description' => 'Модератор',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => 1439966829,
            'updated_at' => 1439966829,
        ]);
        $this->insert('tbl_auth_item',[
            'name' => 'user',
            'type' => 1,
            'description' => 'Пользователь',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => 1439966829,
            'updated_at' => 1439966829,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('tbl_auth_item');
    }
}
