<?php
namespace app\widgets;

use app\models\Interviewee;
use app\models\Questionnaire;
use app\models\Result;
use Yii;
use yii\base\Widget;

class ShowQuestionnaires extends Widget
{
    protected $questionnaires;

    public function init()
    {
        parent::init();

        $interviewee = Interviewee::findOne(['session_id' => Yii::$app->session->id]);

        if (empty($interviewee)) {
            $this->questionnaires = Questionnaire::find()->all();
        } else {
            $passedPolls          = Result::getPassedPolls($interviewee->id);
            $this->questionnaires = Questionnaire::find()->where(['not in', 'id', $passedPolls])->all();
        }
    }

    public function run()
    {
        return $this->render('questionnaires', [
            'models' => $this->questionnaires,
            'result' => new Result,
        ]);
    }
}