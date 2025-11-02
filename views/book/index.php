<?php
use yii\grid\GridView;
use yii\helpers\Html;

/** @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Books';
echo Html::a('Create Book', ['create'], ['class'=>'btn btn-success']);
echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        'id','title','year','isbn',
        ['class'=>yii\grid\ActionColumn::class],
    ],
]);
