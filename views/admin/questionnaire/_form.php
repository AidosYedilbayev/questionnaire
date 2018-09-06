<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Questionnaire */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="questionnaire-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 10,
        'min' => 1,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $modelsHouse[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'description',
        ],
    ]); ?>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Вопросы</th>
            <th style="width: 450px;">Ответы</th>
            <th class="text-center" style="width: 90px;">
                <button type="button" class="add-house btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
            </th>
        </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($questions as $key => $question): ?>
            <tr class="house-item">
                <td class="vcenter">
                    <?php
                    // necessary for update action.
                    if (!$question->isNewRecord) {
                        echo Html::activeHiddenInput($question, "[{$key}]id");
                    }
                    ?>
                    <?= $form->field($question, "[{$key}]description")->label(false)->textInput(['maxlength' => true]) ?>
                </td>
                <td>
                    <?= $this->render('_form-rooms', [
                        'form' => $form,
                        'indexHouse' => $key,
                        'modelsRoom' => $modelsRoom[$indexHouse],
                    ]) ?>
                </td>
                <td class="text-center vcenter" style="width: 90px; verti">
                    <button type="button" class="remove-house btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php DynamicFormWidget::end(); ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
