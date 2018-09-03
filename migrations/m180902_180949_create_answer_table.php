<?php

use yii\db\Migration;

/**
 * Handles the creation of table `answer`.
 */
class m180902_180949_create_answer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('answer', [
            'id'          => $this->primaryKey(),
            'question_id' => $this->integer()->notNull(),
            'title'       => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at'  => $this->timestamp(),
            'updated_at'  => $this->timestamp(),
        ]);

        $this->createIndex(
            'idx-answer-question_id',
            'answer',
            'question_id'
        );

        $this->addForeignKey(
            'fk-answer-question_id',
            'answer',
            'question_id',
            'question',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-answer-question_id',
            'answer'
        );

        $this->dropIndex(
            'idx-answer-question_id',
            'answer'
        );

        $this->dropTable('answer');
    }
}
