<?php

use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;

/**
 * @var yii\web\View           $this
 * @var yii\widgets\ActiveForm $form
 * @var array                  $modelsAnswer
 * @var \app\models\Answer     $modelAnswer
 * @var integer                $indexQuestion
 * @var integer                $indexAnswer
 */
?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody'      => '.container-answers',
    'widgetItem'      => '.answer-item',
    'limit'           => 6,
    'min'             => 1,
    'insertButton'    => '.add-answer',
    'deleteButton'    => '.remove-answer',
    'model'           => $modelsAnswer[0],
    'formId'          => 'dynamic-form',
    'formFields'      => ['title', 'description'],
]); ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Ответ</th>
            <th class="text-center">
                <button type="button" class="add-answer btn btn-success btn-xs">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </th>
        </tr>
        </thead>
        <tbody class="container-answers">
        <?php foreach ($modelsAnswer as $indexAnswer => $modelAnswer): ?>
            <tr class="answer-item">
                <td class="vcenter">
                    <?php
                    // necessary for update action.
                    if (!$modelAnswer->isNewRecord) {
                        echo Html::activeHiddenInput($modelAnswer, "[{$indexQuestion}][{$indexAnswer}]id");
                    }
                    ?>
                    <?= $form->field($modelAnswer, "[{$indexQuestion}][{$indexAnswer}]title")
                        ->label(false)
                        ->textInput([
                            'maxlength' => true,
                            'placeholder' => 'Ответ',
                        ])
                    ?>
                    <?= $form->field($modelAnswer, "[{$indexQuestion}][{$indexAnswer}]description")
                        ->label(false)
                        ->textarea([
                            'rows' => 2,
                            'placeholder' => 'Описание',
                        ])
                    ?>
                </td>
                <td class="text-center vcenter" style="width: 90px;">
                    <button type="button" class="remove-answer btn btn-danger btn-xs">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php DynamicFormWidget::end(); ?>