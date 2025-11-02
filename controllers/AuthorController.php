<?php

namespace app\controllers;

use app\models\Author;
use app\models\AuthorSubscription;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AuthorController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => ['delete' => ['post'], 'subscribe' => ['post']],
            ],
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
            'query'      => Author::find()->orderBy('full_name'),
            'pagination' => ['pageSize' => 50],
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
        $m = new Author();
        if ($m->load(Yii::$app->request->post()) && $m->save()) {
            return $this->redirect(['view', 'id' => $m->id]);
        }

        return $this->render('create', ['model' => $m]);
    }

    public function actionUpdate($id)
    {
        $m = $this->findModel($id);
        if ($m->load(Yii::$app->request->post()) && $m->save()) {
            return $this->redirect(['view', 'id' => $m->id]);
        }

        return $this->render('update', ['model' => $m]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /** Подписка гостя на автора по номеру телефона; JSON-ответ для простоты */
    public function actionSubscribe(int $authorId): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $m = new AuthorSubscription([
            'author_id'  => $authorId,
            'phone'      => (string)Yii::$app->request->post('phone'),
            'created_at' => time(),
        ]);

        if ($m->validate() && $m->save(false)) {
            return ['ok' => true, 'message' => 'Подписка оформлена'];
        }

        return ['ok' => false, 'errors' => $m->getErrors()];
    }

    protected function findModel($id): Author
    {
        $m = Author::findOne($id);
        if (!$m) throw new NotFoundHttpException();
        return $m;
    }
}
