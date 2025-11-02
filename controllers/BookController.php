<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs'  => ['class' => VerbFilter::class, 'actions' => ['delete' => ['post']]],
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['create', 'update', 'delete'],
                'rules' => [['allow' => true, 'roles' => ['@']]],
            ],
        ];
    }

    public function actionIndex()
    {
        $dp = new ActiveDataProvider([
            'query'      => Book::find()->with('authors')->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', ['dataProvider' => $dp]);
    }

    public function actionView($id)
    {
        $m = $this->findModel($id);

        return $this->render('view', ['model' => $m]);
    }

    public function actionCreate()
    {
        $m = new Book();
        if ($m->load(Yii::$app->request->post()) && $m->save()) {
            return $this->redirect(['view', 'id' => $m->id]);
        }
        $authors = ArrayHelper::map(Author::find()->orderBy('full_name')->all(), 'id', 'full_name');
        return $this->render('create', ['model' => $m, 'authors' => $authors]);
    }

    public function actionUpdate($id)
    {
        $m = $this->findModel($id);
        if ($m->load(Yii::$app->request->post()) && $m->save()) {
            return $this->redirect(['view', 'id' => $m->id]);
        }

        $authors = ArrayHelper::map(Author::find()->orderBy('full_name')->all(), 'id', 'full_name');

        return $this->render('update', ['model' => $m, 'authors' => $authors]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id): Book
    {
        $m = Book::find()->with('authors')->where(['id' => $id])->one();
        if (!$m) {
            throw new NotFoundHttpException();
        }

        return $m;
    }
}
