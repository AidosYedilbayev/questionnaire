<?php
namespace app\widgets;

use app\models\Questionnaire;
use app\models\Result;
use yii\base\Widget;

class ShowQuestionnaires extends Widget
{
    protected $questionnaires;

    public function init()
    {
        parent::init();

        $this->questionnaires = Questionnaire::find()->all();
    }

    public function run()
    {
        return $this->render('questionnaires', [
            'models' => $this->questionnaires,
            'result' => new Result,
        ]);
    }
}