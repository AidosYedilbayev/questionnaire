<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Interviewee */

$this->title = 'Create Interviewee';
$this->params['breadcrumbs'][] = ['label' => 'Interviewees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interviewee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
