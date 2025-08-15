<?php
    
    use backend\helpers\ButtonHelper;
    use backend\helpers\ModalHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $searchModel core\edit\search\Content\PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $sites array */
    
    $layoutId = '#cabinet_q-panel__resort';
    $label    = 'Сортировка';
    
    $this->title                   = $label;
    $this->params['breadcrumbs'][] = [
        'label' => 'Панели',
        'url'   => [
            'index',
        ],
    ];
    $this->params['breadcrumbs'][] = $label;

?>


<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title) ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::clearSort($site->id) ?>
        </div>
    </div>

    <div class='card-body'>

        <div class='table-responsive'>
            
            <?php
                try {
                    echo
                    SortableGridView::widget(
                        [
                            'pager' => [
                                'firstPageLabel' => 'в начало',
                                'lastPageLabel'  => 'в конец',
                            ],
                            
                            'dataProvider' => $dataProvider,
                            'filterModel'  => $searchModel,
                            'layout'       => "{summary}\n{pager}\n{items}\n{pager}",
                            'tableOptions' => ['class' => 'table table-striped table-bordered'],
                            'columns'      => [
                                
                                'id',
                                'user_id',
                                'name',
                                'description',
                                'status',
                            
                            ],
                        ],
                    );
                }
                catch (Exception|Throwable $e) {
                    PrintHelper::exception('Widget GridView', $e);
                } ?>
        </div>
    </div>

    <div class='card-footer'>
        Сортировка возможна указанием нового порядкового номера
        либо перетаскиванием предметов мышкой.
        Массовые перетаскивания следует подтвердить нажатием на кнопку
        'Упорядочить сортировку'.
    </div>
</div>



<!-- Modal -->
<?= ModalHelper::setSort($model) ?>
