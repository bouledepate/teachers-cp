<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%profile}}`.
 */
class m220519_075417_add_second_name_column_to_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profile}}', 'second_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%profile}}', 'second_name');
    }
}
