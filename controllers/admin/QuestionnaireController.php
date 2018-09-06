<?php

namespace app\controllers\admin;

use app\models\Answer;
use app\models\Question;
use Yii;
use app\models\Questionnaire;
use app\models\search\QuestionnaireSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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
     */
    public function actionCreate()
    {
        $modelQuestionnaire = new Questionnaire();
        $modelQuestions     = [new Question()];
        $modelAnswers       = [[new Answer()]];

        if ($questionnaire->load(Yii::$app->request->post())) {

            $questions = Model::createMultiple(Question::class);
            Model::loadMultiple($questions, Yii::$app->request->post());

            // validate person and houses models
            $valid = $questionnaire->validate();
            $valid = Model::validateMultiple($questions) && $valid;

            if (isset($_POST['Answer'][0][0])) {
                foreach ($_POST['Answer'] as $indexQuestion => $answers) {
                    foreach ($answers as $indexAnswer => $answer) {
                        $data['Answer'] = $answer;
                        $modelAnswer = new Room;
                        $modelRoom->load($data);
                        $modelsRoom[$indexHouse][$indexRoom] = $modelRoom;
                        $valid = $modelRoom->validate();
                    }
                }
            }

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelPerson->save(false)) {
                        foreach ($modelsHouse as $indexHouse => $modelHouse) {

                            if ($flag === false) {
                                break;
                            }

                            $modelHouse->person_id = $modelPerson->id;

                            if (!($flag = $modelHouse->save(false))) {
                                break;
                            }

                            if (isset($modelsRoom[$indexHouse]) && is_array($modelsRoom[$indexHouse])) {
                                foreach ($modelsRoom[$indexHouse] as $indexRoom => $modelRoom) {
                                    $modelRoom->house_id = $modelHouse->id;
                                    if (!($flag = $modelRoom->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelPerson->id]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }





        return $this->render('create', [
            'model' => $questionnaire,
        ]);
    }

    /**
     * Updates an existing Questionnaire model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Questionnaire model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Questionnaire model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Questionnaire the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Questionnaire::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
