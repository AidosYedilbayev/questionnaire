<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\QuestionnaireSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Опросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questionnaire-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Создать опрос', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],

            'title',
            'description:ntext',
            'created_at',
            'updated_at',

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
