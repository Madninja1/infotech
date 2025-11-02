<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var int|string $year */

$this->title = "Топ-10 авторов за {$year}";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-top-authors">
    <h1><?= Html::encode($this->title) ?></h1>

    <form method="get" action="<?= Url::to(['report/top-authors']) ?>" class="row g-3 mb-3">
        <div class="col-auto"><label for="year" class="col-form-label">Год:</label></div>
        <div class="col-auto">
            <input type="number" name="year" id="year" value="<?= Html::encode($year) ?>"
                   min="1400" max="<?= date('Y') + 1 ?>" class="form-control">
        </div>
        <div class="col-auto"><button type="submit" class="btn btn-primary">Показать</button></div>
    </form>

    <?php if ($dataProvider->getCount() === 0): ?>
        <div class="alert alert-warning">За <?= Html::encode($year) ?> год книги не найдены.</div>
    <?php else: ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'header' => '#',
                    'value' => fn($model, $key, $index) => $index + 1,
                ],
                [
                    'attribute' => 'full_name',
                    'label' => 'Автор',
                ],
                [
                    'attribute' => 'books_count',
                    'label' => 'Количество книг',
                    'format' => 'integer',
                ],
            ],
            'summary' => false,
            'tableOptions' => ['class' => 'table table-bordered table-striped'],
        ]) ?>
    <?php endif; ?>
</div>
