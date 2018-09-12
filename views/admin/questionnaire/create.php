<?php

use yii\helpers\Html;

/**
 * @var yii\web\View              $this
 * @var \app\models\Questionnaire $modelQuestionnaire
 * @var array                     $modelsQuestion
 * @var array                     $modelsAnswer
 */

$this->title                   = 'Создать опрос';
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questionnaire-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelQuestionnaire' => $modelQuestionnaire,
        'modelsQuestion'     => $modelsQuestion,
        'modelsAnswer'       => $modelsAnswer,
    ]) ?>

</div>
