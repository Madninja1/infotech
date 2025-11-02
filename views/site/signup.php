<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
/** @var $model \app\models\SignupForm */
$form = ActiveForm::begin();
echo $form->field($model, 'username');
echo $form->field($model, 'password')->passwordInput();
echo Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-primary']);
ActiveForm::end();
