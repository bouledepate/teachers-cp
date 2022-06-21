<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%group}}`.
 */
class m220621_144209_drop_module_column_from_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%group}}', 'module');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%group}}', 'module', $this->string());
    }
}
