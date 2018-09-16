<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Interviewee;
use app\models\LoginForm;
use app\models\Model;
use app\models\Result;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Saves result of the questionnaire
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionSaveResult()
    {
        if (Yii::$app->request->isPost) {
            /** @var Result[] $models */
            $models = Model::createMultiple(Result::class);
            Model::loadMultiple($models, Yii::$app->request->post());

            $valid     = Model::validateMultiple($models);
            $sessionId = Yii::$app->session->id;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $flag        = true;
                    $interviewee = Interviewee::findOne(['session_id' => $sessionId]);

                    if (empty($interviewee)) {
                        $interviewee             = new Interviewee;
                        $interviewee->session_id = $sessionId;
                        $flag                    = $interviewee->save();
                    }

                    foreach ($models as $model) {
                        if ($flag === false) {
                            break;
                        }

                        $model->interviewee_id = $interviewee->id;

                        if (!($flag = $model->save(false))) {
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->redirect('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
