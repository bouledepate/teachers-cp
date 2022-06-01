<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%group}}`.
 */
class m220601_164746_add_speciality_column_to_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%group}}', 'speciality', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%group}}', 'speciality');
    }
}
