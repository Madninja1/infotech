<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscription}}`.
 */
class m251102_065836_create_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_subscription}}', [
            'id'         => $this->primaryKey(),
            'author_id'  => $this->integer()->notNull(),
            'phone'      => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_sub_author',
            '{{%author_subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'uidx_sub_author_phone',
            '{{%author_subscription}}',
            ['author_id', 'phone'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_sub_author', '{{%author_subscription}}');
        $this->dropTable('{{%author_subscription}}');
    }
}
