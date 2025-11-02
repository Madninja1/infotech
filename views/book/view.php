<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = Html::encode($model->title);
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view container py-4">

    <h1 class="mb-4"><?= Html::encode($model->title) ?></h1>

    <div class="row">
        <!-- Обложка -->
        <div class="col-md-4 mb-3">
            <?php if ($model->cover_url): ?>
                <img src="<?= Html::encode($model->cover_url) ?>" alt="cover"
                     class="img-fluid rounded shadow-sm border">
            <?php else: ?>
                <div class="text-muted fst-italic">Обложка отсутствует</div>
            <?php endif; ?>
        </div>

        <!-- Информация о книге -->
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    [
                        'label' => 'Авторы',
                        'format' => 'raw',
                        'value' => function($m) {
                            $links = array_map(fn($a) => Html::a(Html::encode($a->full_name), ['author/view', 'id' => $a->id]), $m->authors);
                            return implode(', ', $links);
                        },
                    ],
                    [
                        'attribute' => 'year',
                        'label' => 'Год издания',
                    ],
                    'isbn',
                    [
                        'attribute' => 'description',
                        'format' => 'ntext',
                        'label' => 'Описание',
                    ],
                    [
                        'attribute' => 'cover_url',
                        'format' => 'url',
                        'visible' => (bool)$model->cover_url,
                        'label' => 'Ссылка на обложку',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:d.m.Y H:i'],
                        'label' => 'Добавлено',
                    ],
                ],
            ]) ?>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="mt-3">
                    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary me-2']) ?>
                    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <hr class="my-4">

    <!-- Форма подписки -->
    <?php if (Yii::$app->user->isGuest): ?>
        <div class="subscribe-section mt-4">
            <h4>Подписка на авторов этой книги</h4>
            <p class="text-muted">Введите номер телефона, чтобы получать уведомления о новых книгах этих авторов:</p>

            <?php foreach ($model->authors as $author): ?>
                <form method="post" action="<?= Url::to(['author/subscribe', 'authorId' => $author->id]) ?>" class="mb-3 p-3 border rounded bg-light">
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                    <div class="mb-2 fw-semibold"><?= Html::encode($author->full_name) ?></div>
                    <div class="input-group">
                        <input type="tel" name="phone" placeholder="+79991234567"
                               class="form-control" required pattern="^\+?\d{10,15}$">
                        <button type="submit" class="btn btn-success">Подписаться</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
