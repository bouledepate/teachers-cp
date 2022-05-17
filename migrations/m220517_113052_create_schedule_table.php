<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%schedule}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%discipline}}`
 * - `{{%group}}`
 */
class m220517_113052_create_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%schedule}}', [
            'id' => $this->primaryKey(),
            'discipline_id' => $this->integer(),
            'group_id' => $this->integer(),
            'week' => $this->integer(),
            'time' => $this->integer(),
            'day' => $this->integer(),
        ]);

        // creates index for column `discipline_id`
        $this->createIndex(
            '{{%idx-schedule-discipline_id}}',
            '{{%schedule}}',
            'discipline_id'
        );

        // add foreign key for table `{{%discipline}}`
        $this->addForeignKey(
            '{{%fk-schedule-discipline_id}}',
            '{{%schedule}}',
            'discipline_id',
            '{{%discipline}}',
            'id',
            'CASCADE'
        );

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-schedule-group_id}}',
            '{{%schedule}}',
            'group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-schedule-group_id}}',
            '{{%schedule}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%discipline}}`
        $this->dropForeignKey(
            '{{%fk-schedule-discipline_id}}',
            '{{%schedule}}'
        );

        // drops index for column `discipline_id`
        $this->dropIndex(
            '{{%idx-schedule-discipline_id}}',
            '{{%schedule}}'
        );

        // drops foreign key for table `{{%group}}`
        $this->dropForeignKey(
            '{{%fk-schedule-group_id}}',
            '{{%schedule}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-schedule-group_id}}',
            '{{%schedule}}'
        );

        $this->dropTable('{{%schedule}}');
    }
}
