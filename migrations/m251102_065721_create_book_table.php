<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m251102_065721_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id'          => $this->primaryKey(),
            'title'       => $this->string(255)->notNull(),
            'year'        => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'isbn'        => $this->string(32)->unique(),
            'cover_url'   => $this->string(1024),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_book_year', '{{%book}}', 'year');
        $this->createIndex('idx_book_title', '{{%book}}', 'title');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book}}');
    }
}
