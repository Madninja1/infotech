<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\data\ArrayDataProvider;
use yii\db\Query;

class ReportController extends Controller
{
    public function actionTopAuthors(?int $year = null)
    {
        if (!$year) throw new BadRequestHttpException('Укажите ?year=YYYY');

        $rows = (new Query())
            ->select(['a.id', 'a.full_name', 'books_count' => 'COUNT(b.id)'])
            ->from(['a' => '{{%author}}'])
            ->innerJoin(['ba' => '{{%book_author}}'], 'ba.author_id=a.id')
            ->innerJoin(['b' => '{{%book}}'], 'b.id=ba.book_id')
            ->where(['b.year' => $year])
            ->groupBy(['a.id', 'a.full_name'])
            ->orderBy(['books_count' => SORT_DESC, 'a.full_name' => SORT_ASC])
            ->limit(10)
            ->all();

        $provider = new ArrayDataProvider(['allModels' => $rows, 'pagination' => false, 'sort' => false]);

        return $this->render('top-authors', ['dataProvider' => $provider, 'year' => $year]);
    }
}
