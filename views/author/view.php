<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => ['confirm' => 'Are you sure you want to delete this author?', 'method' => 'post'],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => ['id', 'full_name', 'created_at:datetime', 'updated_at:datetime'],
    ]) ?>

    <h3>Books by this author:</h3>
    <ul>
        <?php foreach ($model->books as $book): ?>
            <li><?= Html::a(Html::encode($book->title), ['book/view', 'id' => $book->id]) ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if (Yii::$app->user->isGuest): ?>
        <hr>
        <h4>Subscribe for new books</h4>
        <form method="post" action="<?= \yii\helpers\Url::to(['author/subscribe', 'authorId' => $model->id]) ?>">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
            <input type="text" name="phone" placeholder="+79991234567" required class="form-control mb-2">
            <button type="submit" class="btn btn-success">Subscribe</button>
        </form>
    <?php endif; ?>

</div>
