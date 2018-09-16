<?php

use yii\helpers\Html;

/**
 * @var yii\web\View              $this
 * @var \app\models\Questionnaire $modelQuestionnaire
 * @var array                     $modelsQuestion
 * @var array                     $modelsAnswer
 */

$this->title                   = 'Изменить опрос: ' . $modelQuestionnaire->title;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelQuestionnaire->title];
?>
<div class="questionnaire-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelQuestionnaire' => $modelQuestionnaire,
        'modelsQuestion'     => $modelsQuestion,
        'modelsAnswer'       => $modelsAnswer,
    ]) ?>

</div>
