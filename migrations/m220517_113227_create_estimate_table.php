<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%estimate}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m220517_113227_create_estimate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%estimate}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer(),
            'user_discipline_id' => $this->integer(),
            'created_at' => $this->timestamp(),
            'value' => $this->integer(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-estimate-author_id}}',
            '{{%estimate}}',
            'author_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-estimate-author_id}}',
            '{{%estimate}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-estimate-author_id}}',
            '{{%estimate}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-estimate-author_id}}',
            '{{%estimate}}'
        );

        $this->dropTable('{{%estimate}}');
    }
}
