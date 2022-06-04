<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%group_discipline}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%group}}`
 * - `{{%discipline}}`
 */
class m220604_093848_create_group_discipline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%group_discipline}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer(),
            'discipline_id' => $this->integer(),
        ]);

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-group_discipline-group_id}}',
            '{{%group_discipline}}',
            'group_id'
        );

        // add foreign key for table `{{%group}}`
        $this->addForeignKey(
            '{{%fk-group_discipline-group_id}}',
            '{{%group_discipline}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        // creates index for column `discipline_id`
        $this->createIndex(
            '{{%idx-group_discipline-discipline_id}}',
            '{{%group_discipline}}',
            'discipline_id'
        );

        // add foreign key for table `{{%discipline}}`
        $this->addForeignKey(
            '{{%fk-group_discipline-discipline_id}}',
            '{{%group_discipline}}',
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
            '{{%fk-group_discipline-group_id}}',
            '{{%group_discipline}}'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            '{{%idx-group_discipline-group_id}}',
            '{{%group_discipline}}'
        );

        // drops foreign key for table `{{%discipline}}`
        $this->dropForeignKey(
            '{{%fk-group_discipline-discipline_id}}',
            '{{%group_discipline}}'
        );

        // drops index for column `discipline_id`
        $this->dropIndex(
            '{{%idx-group_discipline-discipline_id}}',
            '{{%group_discipline}}'
        );

        $this->dropTable('{{%group_discipline}}');
    }
}
