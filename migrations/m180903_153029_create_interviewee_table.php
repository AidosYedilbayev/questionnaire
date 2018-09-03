<?php

use yii\db\Migration;

/**
 * Handles the creation of table `interviewee`.
 */
class m180903_153029_create_interviewee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('interviewee', [
            'id'         => $this->primaryKey(),
            'session_id' => $this->string()->notNull(),
            'name'       => $this->string(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);

        $this->createIndex(
            'idx-interviewee-session_id',
            'interviewee',
            'session_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-interviewee-session_id',
            'interviewee'
        );

        $this->dropTable('interviewee');
    }
}
