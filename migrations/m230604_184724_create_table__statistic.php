<?php

use yii\db\Migration;

/**
 * Class m230604_184724_create_table__statistic
 */
class m230604_184724_create_table__statistic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('statistics', [
            'id' => $this->primaryKey()->unsigned(),
            'ip' => $this->string()->notNull(),
            'file_id' => $this->integer(),
            'created_at' => $this->timestamp()->null(),
        ]);

        $this->createIndex('IDX_IP__STATISTICS', 'statistics', 'ip');
        $this->createIndex('IDX_FILE_ID__STATISTICS', 'statistics', 'file_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('IDX_IP__STATISTICS', 'statistics');
        $this->dropIndex('IDX_FILE_ID__STATISTICS', 'statistics');
        $this->dropTable('statistics');
    }
}
