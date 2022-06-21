<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%discipline}}`.
 */
class m220621_144107_add_module_column_to_discipline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%discipline}}', 'module', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%discipline}}', 'module');
    }
}
