<?php
    
    use backend\helpers\FaviconHelper;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\search\Library\ChapterSearch;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    $layoutId = '#frontend_views_book_chapters';
    $label    = Constant::CHAPTER_LABEL;
    

        echo
        GridView::widget(
            [
                'pager'          => [
                    'firstPageLabel' => 'в начало',
                    'lastPageLabel'  => 'в конец',
                ],
                'dataProvider'   => $dataProvider,
                'caption'        => Html::encode($this->title),
                'captionOptions' => [
                    'class' => 'text-end p-2',
                ],
                'summary'        => 'Показаны главы {begin} - {end} из {totalCount}',
                'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                'tableOptions'   => ['class' => 'table table-sm table-striped table-bordered'],
                'columns'        => [
                    ['class' => SerialColumn::class],
                    [
                        'attribute' => 'name',
                        'label'     => 'Название',
                        'value'     => static function (
                            Chapter $model,
                        ) {
                            return Html::a(
                                    Html::encode
                                    (
                                        $model->name,
                                    ),
                                    [
                                        '/chapter/view',
                                        'id' => $model->id,
                                    ],
                                );
                            
                        },
                        'format'    => 'raw',
                    ],
                ],
            ],
        );
