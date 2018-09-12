<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
class Question extends ActiveRecord
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
            [['questionnaire_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questionnaire::class, 'targetAttribute' => ['questionnaire_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'questionnaire_id' => 'Опрос',
            'title'            => 'Вопрос',
            'description'      => 'Описание',
            'created_at'       => 'Создано',
            'updated_at'       => 'Обновлено',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value'      => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::class, ['id' => 'questionnaire_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['question_id' => 'id']);
    }
}
