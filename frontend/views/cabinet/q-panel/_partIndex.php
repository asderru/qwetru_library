<?php
    
    use core\edit\entities\Addon\Panel;
    use core\edit\search\Addon\PanelSearch;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $searchModel PanelSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    $layoutId = '#cabinet_q-panel_partIndex';
    
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
                    'class' => 'bg-secondary text-white p-2',
                ],
                'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                'summaryOptions' => [
                    'class' => 'bg-secondary text-white p-1',
                ],
                'tableOptions'   => ['class' => 'table table-striped table-bordered'],
                'columns'        => [
                    ['class' => SerialColumn::class],
                    [
                        'attribute' => 'name',
                        'label'     => 'Название',
                        'value'     => static function (
                            Panel $model,
                        ) {
                            return Html::a(
                                Html::encode
                                (
                                    $model->name,
                                ),
                                [
                                    'view',
                                    'id' => $model->id,
                                ],
                            );
                            
                        },
                        'format'    => 'raw',
                    ],
                    [
                        'label' => 'Баннеры',
                        'value' => static function (Panel $model) {
                            return $model->getBannerNames();
                        },
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
