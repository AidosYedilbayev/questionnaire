<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int           $id
 * @property int           $questionnaire_id
 * @property string        $title
 * @property string        $description
 * @property string        $created_at
 * @property string        $updated_at
 * @property Answer[]      $answers
 * @property Questionnaire $questionnaire
 * @property Result[]      $results
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'title'], 'required'],
            [['questionnaire_id'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'title'            => 'Title',
            'description'      => 'Description',
            'created_at'       => 'Created At',
            'updated_at'       => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::className(), ['question_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\QuestionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\QuestionQuery(get_called_class());
    }
}
