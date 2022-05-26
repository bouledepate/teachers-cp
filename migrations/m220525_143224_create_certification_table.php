<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%certification}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%group}}`
 * - `{{%discipline}}`
 */
class m220525_143224_create_certification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certification}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer(),
            'discipline_id' => $this->integer(),
            'type' => $this->integer()->notNull(),
            'subtype' => $this->integer(),
            'period' => $this->integer(),
            'date' => $this->timestamp()
        ]);

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-certification-group_id}}',
            '{{%certification}}',
            'group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-certification-group_id}}',
            '{{%certification}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        // creates index for column `discipline_id`
        $this->createIndex(
            '{{%idx-certification-discipline_id}}',
            '{{%certification}}',
            'discipline_id'
        );

        // add foreign key for table `{{%discipline}}`
        $this->addForeignKey(
            '{{%fk-certification-discipline_id}}',
            '{{%certification}}',
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
        // drops foreign key for table `{{%group}}`
        $this->dropForeignKey(
            '{{%fk-certification-group_id}}',
            '{{%certification}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-certification-group_id}}',
            '{{%certification}}'
        );

        // drops foreign key for table `{{%discipline}}`
        $this->dropForeignKey(
            '{{%fk-certification-discipline_id}}',
            '{{%certification}}'
        );

        // drops index for column `discipline_id`
        $this->dropIndex(
            '{{%idx-certification-discipline_id}}',
            '{{%certification}}'
        );

        $this->dropTable('{{%certification}}');
    }
}
