<?php

use yii\db\Migration;

/**
 * Handles the creation of table `result`.
 */
class m180903_154408_create_result_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('result', [
            'id'               => $this->primaryKey(),
            'questionnaire_id' => $this->integer()->notNull(),
            'question_id'      => $this->integer()->notNull(),
            'answer_id'        => $this->integer()->notNull(),
            'interviewee_id'   => $this->integer()->notNull(),
            'created_at'       => $this->timestamp(),
            'updated_at'       => $this->timestamp(),
        ]);

        $this->createIndex(
            'idx-result-questionnaire_id',
            'result',
            'questionnaire_id'
        );

        $this->createIndex(
            'idx-result-question_id',
            'result',
            'question_id'
        );

        $this->createIndex(
            'idx-result-answer_id',
            'result',
            'answer_id'
        );

        $this->createIndex(
            'idx-result-interviewee_id',
            'result',
            'interviewee_id'
        );

        $this->addForeignKey(
            'fk-result-questionnaire_id',
            'result',
            'questionnaire_id',
            'questionnaire',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-result-question_id',
            'result',
            'question_id',
            'question',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-result-answer_id',
            'result',
            'answer_id',
            'answer',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-result-interviewee_id',
            'result',
            'interviewee_id',
            'interviewee',
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
            'fk-result-questionnaire_id',
            'result'
        );

        $this->dropForeignKey(
            'fk-result-question_id',
            'result'
        );

        $this->dropForeignKey(
            'fk-result-answer_id',
            'result'
        );

        $this->dropForeignKey(
            'fk-result-interviewee_id',
            'result'
        );

        $this->dropIndex(
            'idx-result-questionnaire_id',
            'result'
        );

        $this->dropIndex(
            'idx-result-question_id',
            'result'
        );

        $this->dropIndex(
            'idx-result-answer_id',
            'result'
        );

        $this->dropIndex(
            'idx-result-interviewee_id',
            'result'
        );

        $this->dropTable('result');
    }
}
