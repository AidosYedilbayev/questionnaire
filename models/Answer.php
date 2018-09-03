<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property int      $id
 * @property int      $question_id
 * @property string   $title
 * @property string   $description
 * @property string   $created_at
 * @property string   $updated_at
 * @property Question $question
 * @property Result[] $results
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'title'], 'required'],
            [['question_id'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'question_id' => 'Question ID',
            'title'       => 'Title',
            'description' => 'Description',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
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
    public function getResults()
    {
        return $this->hasMany(Result::className(), ['answer_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\AnswerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AnswerQuery(get_called_class());
    }
}
