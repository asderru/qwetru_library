<?php
    
    use backend\helpers\ButtonHelper;
    use core\edit\search\Addon\PanelSearch;
    use core\helpers\ParametrHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $searchModel PanelSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    
    $layoutId = '#cabinet_q-panel__index';
    $label    = 'Рекламные панели';
    
    $this->title                   = $label;
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class='small text-end'>
    Рекламные панели
</div>

<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title) ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?php
                if (!ParametrHelper::isServer()) { ?>
                    <?= ButtonHelper::resort
                    (
                        Constant::SITE_ID, 'Сортировать',
                    )
                    ?>
                    <?php
                }
            ?>
            <?= ButtonHelper::create('Добавить панель') ?>
            <?= ButtonHelper::refresh() ?>
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
        <p>
        </p>

    </div>

    <div class='table-responsive'>
        <?= $this->render('@app/views/addon/panel/_partIndex', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]) ?>
    </div>

</div>
