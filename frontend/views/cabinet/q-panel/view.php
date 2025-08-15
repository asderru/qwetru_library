<?php
    
    use backend\helpers\ButtonHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Addon\Panel */
    /* @var $actionId string */
    
    $layoutId = '#cabinet_q-panel__view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Рекламные панели', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<div class='card rounded-top rounded-1'>
    
    <?= $this->render(
        '@app/views/layouts/partials/_viewHeader',
        [
            'id'     => $model->id,
            'title'  => $model->name,
            'status' => $model->status,
        ],
    ) ?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        
        <?= Html::a(
            'Поменять баннеры',
            [
                'insert',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-sm btn-info',
            ],
        )
        ?>
        <?= ButtonHelper::create('Добавить панель') ?>
        <?= ButtonHelper::delete($model) ?>
    </div>

</div>

<div class='card rounded-0'>

    <div class='d-flex justify-content-between px-2'>
        <span>
            <?= $actionId ?>
        </span>
        <span>
            <?= $layoutId ?>
        </span>
    </div>

    <div class='card-body'>
        
        <?= $this->render(
            '@app/views/addon/panel/_partView',
            [
                'model' => $model,
            ],
        ) ?>

    </div>

</div>

<div class='small text-end'>
    
    <?= $layoutId ?>

</div>
