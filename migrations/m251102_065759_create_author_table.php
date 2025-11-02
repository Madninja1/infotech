<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author}}`.
 */
class m251102_065759_create_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id'         => $this->primaryKey(),
            'full_name'  => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_author_full_name', '{{%author}}', 'full_name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author}}');
    }
}
