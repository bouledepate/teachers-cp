<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_discipline}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%discipline}}`
 */
class m220517_133223_create_user_discipline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_discipline}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'discipline_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_discipline-user_id}}',
            '{{%user_discipline}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_discipline-user_id}}',
            '{{%user_discipline}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `discipline_id`
        $this->createIndex(
            '{{%idx-user_discipline-discipline_id}}',
            '{{%user_discipline}}',
            'discipline_id'
        );

        // add foreign key for table `{{%discipline}}`
        $this->addForeignKey(
            '{{%fk-user_discipline-discipline_id}}',
            '{{%user_discipline}}',
            'discipline_id',
            '{{%discipline}}',
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
            '{{%fk-user_discipline-user_id}}',
            '{{%user_discipline}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_discipline-user_id}}',
            '{{%user_discipline}}'
        );

        // drops foreign key for table `{{%discipline}}`
        $this->dropForeignKey(
            '{{%fk-user_discipline-discipline_id}}',
            '{{%user_discipline}}'
        );

        // drops index for column `discipline_id`
        $this->dropIndex(
            '{{%idx-user_discipline-discipline_id}}',
            '{{%user_discipline}}'
        );

        $this->dropTable('{{%user_discipline}}');
    }
}
