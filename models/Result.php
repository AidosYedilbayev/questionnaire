<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "result".
 *
 * @property int           $id
 * @property int           $questionnaire_id
 * @property int           $question_id
 * @property int           $answer_id
 * @property int           $interviewee_id
 * @property string        $created_at
 * @property string        $updated_at
 * @property Answer        $answer
 * @property Interviewee   $interviewee
 * @property Question      $question
 * @property Questionnaire $questionnaire
 */
class Result extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'question_id', 'answer_id', 'interviewee_id'], 'required'],
            [['questionnaire_id', 'question_id', 'answer_id', 'interviewee_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['answer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Answer::className(), 'targetAttribute' => ['answer_id' => 'id']],
            [['interviewee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Interviewee::className(), 'targetAttribute' => ['interviewee_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
            [['questionnaire_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questionnaire::className(), 'targetAttribute' => ['questionnaire_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'questionnaire_id' => 'Questionnaire ID',
            'question_id'      => 'Question ID',
            'answer_id'        => 'Answer ID',
            'interviewee_id'   => 'Interviewee ID',
            'created_at'       => 'Created At',
            'updated_at'       => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answer::className(), ['id' => 'answer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInterviewee()
    {
        return $this->hasOne(Interviewee::className(), ['id' => 'interviewee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ResultQuery(get_called_class());
    }
}
