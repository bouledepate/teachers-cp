<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%group_discipline}}`.
 */
class m220604_093508_drop_group_discipline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%group_discipline}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%group_discipline}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
