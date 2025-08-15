<?php
    
    use backend\helpers\ButtonHelper;
    use core\edit\forms\Addon\InsertBannerForm;
    use core\edit\search\Addon\BannerSearch;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\CheckboxColumn;
    use yii\grid\GridView;
    
    /* @var $this yii\web\View */
    /* @var $searchModel BannerSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\entities\Addon\Panel */
    /* @var $checked array */
    /* @var $actionId string */
    
    $layoutId = '#cabinet_q-panel__insert';
    
    $this->title                   = 'Баннеры';
    $this->params['breadcrumbs'][] = ['label' => 'Рекламные панели', 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => 'Панель ' . $model->name,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            Выбор похожих или сопутствующих товаров/услуг для <?= Html::a(
                Html::encode(
                    $model->name,
                ),
                [
                    'view',
                    'id' => $model->id,
                ],
            
            ) ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::collapse() ?>
        </div>
    </div>

    <div class='small d-flex justify-content-between px-2'>
        <span>
            <?= $actionId ?>
        </span>
        <span>
            <?= $layoutId ?>
        </span>
    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>

    </div>

    <div class='card mb-3'>

        <div class='card-header bg-gray'>
            <strong>
                Отметить выбранное
            </strong>
        </div>
        <div class='card-body'>
            <div class='table-responsive'>
                
                <?php
                    try {
                        echo
                        GridView::widget(
                            [
                                'id'             => 'grid',
                                'pager'          => [
                                    'firstPageLabel' => 'в начало',
                                    'lastPageLabel'  => 'в конец',
                                ],
                                'dataProvider'   => $dataProvider,
                                'filterModel'    => $searchModel,
                                'caption'        => 'Товары/услуги',
                                'captionOptions' => [
                                    'class' => 'text-start p-2',
                                ],
                                'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                                'summaryOptions' => [
                                    'class' => 'bg-secondary text-white p-1',
                                ],
                                'tableOptions'   => ['class' => 'table table-striped table-bordered'],
                                'columns'        => [
                                    [
                                        'class' => CheckboxColumn::class,
                                    ],
                                    'id',
                                    [
                                        'attribute' => 'name',
                                        'label'     => 'Название',
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
                ?>

            </div>
        </div>
        <div class="card-footer text-center">
            <?= Html::button(
                'Добавить баннеры в панель',
                [
                    'class' => 'btn btn-secondary',
                    'id'    => 'check-button',
                ],
            ) ?>
        </div>
    </div>
</div>



<?php
    $script = <<< JS
$('#check-button').on('click', function() {
    var keys = $("#grid").yiiGridView('getSelectedRows');
    var modelId = $model->id;
    

    // Отправляем выбранные ID на действие "check" через AJAX.
    $.ajax({
        url: '/addon/panel/banner',
        data: {keylist: keys, modelId: modelId},
        type: 'GET',
        // dataType: 'json',
        success: function (result) {
            // alert(keys);
            console.log(keys);
        },
        error: function () {
            console.log(keys);
        }
    });
});

JS;
    $this->registerJs($script);
?>
