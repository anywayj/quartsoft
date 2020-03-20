<?

use yii\grid\GridView;

?>

<div class="body-content">
    <div class="row">
        <? if (count($articleQuery)) { ?>
            <?= $this->render('_searchArticle', ['model' => $searchModel]); ?>
        <? } ?>

        <h2>Активные публикации статей</h2>

        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => false,
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover'
                ],
                'columns' => [
                    [
                        'attribute' => 'Наименование',
                        'format' => 'raw',
                        'value' => function($data) {
                            return $data->article_name;
                        },
                    ],

                    [
                        'attribute' => 'Описание',
                        'format' => 'raw',
                        'value' => function($data) {
                            return $data->article_text;
                        },
                    ],

                    [
                        'attribute' => 'Дата',
                        'format' => ['date', 'php:Y/m/d'],
                        'value' => function($data) {
                            return $data->article_created_at;
                        },
                    ],

                    [
                        'attribute' => 'Автор',
                        'value' => function($data) {
                            return $data->user->recordUser->firstname;
                        },
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>