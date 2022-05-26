<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_certification}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%certification}}`
 * - `{{%user}}`
 */
class m220525_143421_create_user_certification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_certification}}', [
            'id' => $this->primaryKey(),
            'certification_id' => $this->integer(),
            'user_id' => $this->integer(),
            'mark' => $this->integer(),
            'ticket' => $this->integer(),
        ]);

        // creates index for column `certification_id`
        $this->createIndex(
            '{{%idx-user_certification-certification_id}}',
            '{{%user_certification}}',
            'certification_id'
        );

        // add foreign key for table `{{%certification}}`
        $this->addForeignKey(
            '{{%fk-user_certification-certification_id}}',
            '{{%user_certification}}',
            'certification_id',
            '{{%certification}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_certification-user_id}}',
            '{{%user_certification}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_certification-user_id}}',
            '{{%user_certification}}',
            'user_id',
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
        // drops foreign key for table `{{%certification}}`
        $this->dropForeignKey(
            '{{%fk-user_certification-certification_id}}',
            '{{%user_certification}}'
        );

        // drops index for column `certification_id`
        $this->dropIndex(
            '{{%idx-user_certification-certification_id}}',
            '{{%user_certification}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_certification-user_id}}',
            '{{%user_certification}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_certification-user_id}}',
            '{{%user_certification}}'
        );

        $this->dropTable('{{%user_certification}}');
    }
}
