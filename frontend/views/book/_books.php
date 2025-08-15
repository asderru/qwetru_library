<?php
    
    use backend\helpers\FaviconHelper;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Library\Book;
    use core\edit\search\Library\BookSearch;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $url string */
    /* @var $searchModel BookSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    $layoutId = '#library_book_partIndex';
    $label    = Constant::BOOK_LABEL;
    
    try {
        echo
        GridView::widget(
            [
                'pager'          => [
                    'firstPageLabel' => 'в начало',
                    'lastPageLabel'  => 'в конец',
                ],
                'dataProvider'   => $dataProvider,
                'filterModel'    => $searchModel,
                'caption'        => Html::encode($this->title),
                'captionOptions' => [
                    'class' => 'text-end p-2',
                ],
                'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                'summaryOptions' => [
                    'class' => 'bg-secondary text-white p-1',
                ],
                'tableOptions'   => ['class' => 'table table-striped table-bordered'],
                'columns'        => [
                    ['class' => SerialColumn::class],
                    [
                        'value'          => static function (Book $model) {
                            return
                                Html::img(
                                    $model->getImgUrl(1),
                                [
                                    'class' => 'img-fluid',
                                ],
                                )
                                ??
                                '<i class="bi bi-eye-fill"></i>';
                        },
                        'label'          => 'Изображение',
                        'format'         => 'raw',
                        'contentOptions' => [
                            'style' => 'width: 130px',
                        ],
                    ],
                    [
                        'attribute' => 'id',
                        'label'     => 'Том',
                        'value'     => static function (Book $model) {
                            $name = $model->getDepthName(2);
                            return Html::a(
                                    $name,
                                    [
                                        'view',
                                        'id' => $model->id,
                                    ]
                            ) . '<hr>' . FormatHelper::asDescription(
                                $model, 10
                                );
                        },
                        'filter'    => $searchModel::booksList(Constant::STATUS_ACTIVE),
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'author_id',
                        'value'     => static function (Book $model) {
                            return Html::a(
                                $model->author?->name,
                                [
                                    '/author/view',
                                    'id' => $model->author?->id,
                                ],
                            );
                        },
                        'filter'    => $searchModel::authorsList(Constant::STATUS_ACTIVE),
                        'format'    => 'raw',
                    ],
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'GridView-widget ' . $layoutId, $e,
        );
    }
