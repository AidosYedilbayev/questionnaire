<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "interviewee".
 *
 * @property int      $id
 * @property string   $session_id
 * @property string   $name
 * @property string   $created_at
 * @property string   $updated_at
 * @property Result[] $results
 */
class Interviewee extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interviewee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['session_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['session_id', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'session_id' => 'Session ID',
            'name'       => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getResults()
    {
        return $this->hasMany(Result::class, ['interviewee_id' => 'id']);
    }
}
