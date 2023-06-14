<?php

use yii\db\Migration;

/**
 * Class m230604_184544_create_table__files
 */
class m230604_184544_create_table__files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('files', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'extension' => $this->string(10)->null(),
            'path' => $this->string()->null(),
            'mime' => $this->string()->null(),
            'size' => $this->bigInteger()->unsigned()->notNull()->comment('in bytes'),
            'mtime' => $this->timestamp()->null()->comment('time of last modification content of file (Unix timestamp)'),
            'ctime' => $this->timestamp()->null()->comment('time of last inode change: permission, owner, groups (Unix timestamp)'),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ]);

        $this->createIndex('IDX_FILENAME__FILES', 'files', ['name', 'extension'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('IDX_FILENAME__FILES', 'files');
        $this->dropTable('files');
    }
}
