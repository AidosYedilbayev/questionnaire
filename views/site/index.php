<?php

use app\widgets\ShowQuestionnaires;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Добро пожаловать!</h1>

        <p class="lead">Пройдите пожалуйста наши опросы</p>
    </div>

    <div class="body-content">

        <?= ShowQuestionnaires::widget() ?>

    </div>
</div>
