<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                $this
 * @var \app\models\Questionnaire[] $models
 * @var \app\models\Result          $result
 */

?>
<div class="row">
    <?php foreach ($models as $model): ?>
        <div class="col-lg-4">
            <h2><?= $model->title ?></h2>

            <p><?= $model->description ?></p>

            <?php Modal::begin([
                'header'       => "<h2>$model->title</h2>",
                'size'         => 'modal-lg',
                'toggleButton' => [
                    'label' => 'Пройти опрос',
                    'tag'   => 'button',
                    'class' => 'btn btn-primary',
                ],
            ]); ?>

            <h3><?= $model->description ?></h3>

            <?php $form = ActiveForm::begin([
                'id'     => "questionnaire-$model->id",
                'action' => 'site/save-result',
            ]); ?>

            <?php foreach ($model->questions as $questionIndex => $question): ?>
                <?= $form->field($result, "[{$questionIndex}]questionnaire_id")
                    ->hiddenInput(['value' => $model->id])
                    ->label(false);
                ?>

                <?= $form->field($result, "[{$questionIndex}]question_id")
                    ->hiddenInput(['value' => $question->id])
                    ->label(false);
                ?>

                <p style="font-size: 18px; margin-top: 15px;"><b><?= $question->title ?></b></p>
                <p style="font-size: 16px; margin: -10px 0px;"><i><?= $question->description ?></i></p>

                <?= $form->field($result, "[{$questionIndex}]answer_id")
                    ->label(false)
                    ->radioList($question->getArrayAnswers(), [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return sprintf(
                                '<div class="radio"><label>%s%s</label></div>',
                                Html::radio($name, $checked, ['value' => $value]),
                                $label
                            );
                        },
                    ]);
                ?>
            <?php endforeach; ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php Modal::end() ?>
        </div>
    <?php endforeach; ?>
</div>
