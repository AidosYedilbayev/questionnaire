<?php

use yii\db\Migration;

/**
 * Handles the creation of table `question`.
 */
class m180902_175442_create_question_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('question', [
            'id'               => $this->primaryKey(),
            'questionnaire_id' => $this->integer()->notNull(),
            'title'            => $this->string()->notNull(),
            'description'      => $this->text(),
            'created_at'       => $this->timestamp(),
            'updated_at'       => $this->timestamp(),
        ]);

        $this->createIndex(
            'idx-question-questionnaire_id',
            'question',
            'questionnaire_id'
        );

        $this->addForeignKey(
            'fk-question-questionnaire_id',
            'question',
            'questionnaire_id',
            'questionnaire',
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
            'fk-question-questionnaire_id',
            'question'
        );

        $this->dropIndex(
            'idx-question-questionnaire_id',
            'question'
        );

        $this->dropTable('question');
    }
}
