<?php
namespace app\controllers\admin;

use app\models\Answer;
use app\models\search\QuestionnaireSearch;
use app\models\Question;
use app\models\Questionnaire;
use Yii;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * QuestionnaireController implements the CRUD actions for Questionnaire model.
 */
class QuestionnaireController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Questionnaire models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new QuestionnaireSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Questionnaire model.
     *
     * @param  integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $questionnaire = $this->findModel($id);
        $questions     = $questionnaire->getQuestions();

        return $this->render('view', [
            'questionnaire' => $questionnaire,
            'questions'     => $questions,
        ]);
    }

    /**
     * Creates a new Questionnaire model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $modelQuestionnaire = new Questionnaire();
        $modelsQuestion     = [new Question()];
        $modelsAnswer       = [[new Answer()]];

        if ($modelQuestionnaire->load(Yii::$app->request->post())) {

            $modelsQuestion = Model::createMultiple(Question::class);
            Model::loadMultiple($modelsQuestion, Yii::$app->request->post());

            // validate questionnaire and questions models
            $valid = $modelQuestionnaire->validate();
            $valid = Model::validateMultiple($modelsQuestion) && $valid;

            if (isset($_POST['Answer'][0][0])) {
                foreach ($_POST['Answer'] as $indexQuestion => $answers) {
                    foreach ($answers as $indexAnswer => $answer) {
                        $data['Answer'] = $answer;
                        $modelAnswer    = new Answer;

                        $modelAnswer->load($data);

                        $modelsAnswer[$indexQuestion][$indexAnswer] = $modelAnswer;
                        $valid                                      = $modelAnswer->validate();
                    }
                }
            }

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelQuestionnaire->save(false)) {
                        foreach ($modelsQuestion as $indexQuestion => $modelQuestion) {
                            if ($flag === false) {
                                break;
                            }

                            /** @var Question $modelQuestion */
                            $modelQuestion->questionnaire_id = $modelQuestionnaire->id;

                            if (!($flag = $modelQuestionnaire->save(false))) {
                                break;
                            }

                            if (isset($modelsAnswer[$indexQuestion]) && is_array($modelsAnswer[$indexQuestion])) {
                                foreach ($modelsAnswer[$indexQuestion] as $modelAnswer) {
                                    /** @var Answer $modelAnswer */
                                    $modelAnswer->question_id = $modelQuestion->id;
                                    if (!($flag = $modelAnswer->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelQuestionnaire->id]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'modelQuestionnaire' => $modelQuestionnaire,
            'modelsQuestion'     => (empty($modelsQuestion)) ? [new Question()] : $modelsQuestion,
            'modelsAnswer'       => (empty($modelsAnswer)) ? [[new Answer()]] : $modelsAnswer,
        ]);
    }

    /**
     * Updates an existing Questionnaire model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param  integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $modelQuestionnaire = $this->findModel($id);
        $modelsQuestion     = $modelQuestionnaire->questions;
        $modelsAnswer       = [];
        $oldAnswers         = [];

        if (!empty($modelsQuestion)) {
            foreach ($modelsQuestion as $indexQuestion => $modelQuestion) {
                $answers                      = $modelQuestion->answers;
                $modelsAnswer[$indexQuestion] = $answers;
                $oldAnswers                   = ArrayHelper::merge(ArrayHelper::index($answers, 'id'), $oldAnswers);
            }
        }

        if ($modelQuestionnaire->load(Yii::$app->request->post())) {
            // reset
            $modelsAnswer = [];

            $oldQuestionIDs = ArrayHelper::map($modelsQuestion, 'id', 'id');
            $modelsQuestion = Model::createMultiple(Question::class, $modelsQuestion);

            Model::loadMultiple($modelsQuestion, Yii::$app->request->post());

            $deletedQuestionIDs = array_diff($oldQuestionIDs, array_filter(ArrayHelper::map($modelsQuestion, 'id', 'id')));

            // validate questionnaire and questions models
            $valid = $modelQuestionnaire->validate();
            $valid = Model::validateMultiple($modelsQuestion) && $valid;

            $answersIDs = [];
            if (isset($_POST['Room'][0][0])) {
                foreach ($_POST['Room'] as $indexQuestion => $answers) {
                    $answersIDs = ArrayHelper::merge($answersIDs, array_filter(ArrayHelper::getColumn($answers, 'id')));
                    foreach ($answers as $indexAnswer => $answer) {
                        $data['Room'] = $answer;
                        $modelAnswer  = (isset($answer['id']) && isset($oldAnswers[$answer['id']])) ? $oldAnswers[$answer['id']] : new Answer;

                        $modelAnswer->load($data);

                        $modelsAnswer[$indexQuestion][$indexAnswer] = $modelAnswer;
                        $valid                                      = $modelAnswer->validate();
                    }
                }
            }

            $oldAnswersIDs     = ArrayHelper::getColumn($oldAnswers, 'id');
            $deletedAnswersIDs = array_diff($oldAnswersIDs, $answersIDs);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelQuestionnaire->save(false)) {
                        if (!empty($deletedAnswersIDs)) {
                            Answer::deleteAll(['id' => $deletedAnswersIDs]);
                        }

                        if (!empty($deletedQuestionIDs)) {
                            Question::deleteAll(['id' => $deletedQuestionIDs]);
                        }

                        foreach ($modelsQuestion as $indexQuestion => $modelQuestion) {
                            if ($flag === false) {
                                break;
                            }

                            /** @var Question $modelQuestion */
                            $modelQuestion->questionnaire_id = $modelQuestionnaire->id;

                            if (!($flag = $modelQuestion->save(false))) {
                                break;
                            }

                            if (isset($modelsAnswer[$indexQuestion]) && is_array($modelsAnswer[$indexQuestion])) {
                                foreach ($modelsAnswer[$indexQuestion] as $indexAnswer => $modelAnswer) {
                                    /** @var Answer $modelAnswer */
                                    $modelAnswer->question_id = $modelQuestion->id;
                                    if (!($flag = $modelAnswer->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelQuestionnaire->id]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'modelQuestionnaire' => $modelQuestionnaire,
            'modelsQuestion'     => (empty($modelsQuestion)) ? [new Question] : $modelsQuestion,
            'modelsAnswer'       => (empty($modelsAnswer)) ? [[new Answer]] : $modelsAnswer,
        ]);
    }

    /**
     * Deletes an existing Questionnaire model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param  integer $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $name  = $model->title;

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', "Опрос <strong> $name </strong> успешно удален");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Questionnaire model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Questionnaire
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Questionnaire::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена');
    }
}
