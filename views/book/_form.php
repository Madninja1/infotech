<?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'year') ?>
<?= $form->field($model, 'isbn') ?>
<?= $form->field($model, 'description')->textarea() ?>

<?= $form->field($model, 'cover_url')->textInput(['placeholder' => 'https://...']) ?>

<?= $form->field($model, 'coverFile')->fileInput() ?>

<?= $form->field($model, 'authorIds')->checkboxList(
    \yii\helpers\ArrayHelper::map(
        \app\models\Author::find()->orderBy('full_name')->all(),
        'id',
        'full_name'
    )
) ?>

<?= \yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
<?php \yii\widgets\ActiveForm::end(); ?>
