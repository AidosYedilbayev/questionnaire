<?php

use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View              $this
 * @var app\models\Questionnaire  $modelQuestionnaire
 * @var yii\widgets\ActiveForm    $form
 * @var \app\models\Questionnaire $modelQuestionnaire
 * @var array                     $modelsQuestion
 * @var integer                   $indexQuestion
 * @var \app\models\Question      $modelQuestion
 * @var array                     $modelsAnswer
 */
?>

<div class="questionnaire-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelQuestionnaire, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelQuestionnaire, 'description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody'      => '.container-items',
        'widgetItem'      => '.question-item',
        'limit'           => 20,
        'min'             => 1,
        'insertButton'    => '.add-question',
        'deleteButton'    => '.remove-question',
        'model'           => $modelsQuestion[0],
        'formId'          => 'dynamic-form',
        'formFields'      => ['title'],
    ]); ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>questions</th>
            <th style="width: 450px;">answers</th>
            <th class="text-center" style="width: 90px;">
                <button type="button" class="add-question btn btn-success btn-xs"><span class="fa fa-plus"></span>
                </button>
            </th>
        </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($modelsQuestion as $indexQuestion => $modelQuestion): ?>
            <tr class="question-item">
                <td class="vcenter">
                    <?php
                    // necessary for update action.
                    if (!$modelQuestion->isNewRecord) {
                        echo Html::activeHiddenInput($modelQuestion, "[{$indexQuestion}]id");
                    }
                    ?>
                    <?= $form->field($modelQuestion, "[{$indexQuestion}]title")->label(false)->textInput(['maxlength' => true]) ?>
                </td>
                <td>
                    <?= $this->render('_form-answers', [
                        'form'          => $form,
                        'indexQuestion' => $indexQuestion,
                        'modelsAnswer'  => $modelsAnswer[$indexQuestion],
                    ]) ?>
                </td>
                <td class="text-center vcenter" style="width: 90px; verti">
                    <button type="button" class="remove-question btn btn-danger btn-xs">
                        <span class="fa fa-minus"></span>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton($modelQuestionnaire->isNewRecord ? 'Добавить' : 'Обновить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
