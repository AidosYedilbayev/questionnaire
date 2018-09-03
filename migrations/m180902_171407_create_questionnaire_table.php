<?php

use yii\db\Migration;

/**
 * Handles the creation of table `questionnaire`.
 */
class m180902_171407_create_questionnaire_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('questionnaire', [
            'id'          => $this->primaryKey(),
            'title'       => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at'  => $this->timestamp(),
            'updated_at'  => $this->timestamp(),
        ]);

        $this->createIndex(
            'idx-questionnaire-title',
            'questionnaire',
            'title'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-questionnaire-title',
            'questionnaire'
        );

        $this->dropTable('questionnaire');
    }
}
