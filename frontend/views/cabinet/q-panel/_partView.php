<?php
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Addon\Panel */
    
    $layoutId = '#cabinet_q-panel_partView';

?>

<div class='row'>

    <div class='col-xl-6'>
        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Информация
                </strong>
            </div>
            <div class='card-body'>
                
                <?php
                    try {
                        echo DetailView::widget(
                            [
                                'model'      => $model,
                                'attributes' => [
                                    'id',
                                    [
                                        'attribute' => 'site.name',
                                        'label'     => 'Сайт',
                                    ],
                                    'name',
                                    [
                                        'attribute' => 'description',
                                        'format'    => 'raw',
                                    ],
                                    'sort',
                                    [
                                        'attribute' => 'updated_at',
                                        'format'    => 'dateTime',
                                    ],
                                ],
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'DetailView-widget ' . $layoutId, $e,
                        );
                    } ?>
            </div>
        </div>
    </div>
    <div class='col-xl-6'>
        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Баннеры
                </strong>
            </div>
            <div class='card-body'>
                
                
                <?php
                    $i = 1;
                    try {
                        foreach ($model->getBanners()->all() as $banner) :
                            ?>
                            <div class='row'>
                                <div class='col-lg-7 col-md-9 col-10'>
                                    <?= $this->render(
                                        '@app/views/addon/banner/_bannerView',
                                        [
                                            'model' => $banner,
                                        ],
                                    ) ?>

                                </div>
                                <div class='col-lg-5 col-md-3 col-2'>
                                    <?php
                                        if ($i > 1):?>
                                            <?= Html::a(
                                                '<i class="bi bi-arrow-up-square-fill"></i>',
                                                [
                                                    'move-banner-up',
                                                    'id'       => $model->id,
                                                    'bannerId' => $banner->id,
                                                ],
                                                [
                                                    'class'             => 'btn btn-primary btn-lg ',
                                                    'data-method'       => 'POST',
                                                    'data-bs-toggle'    => 'tooltip',
                                                    'data-bs-placement' => 'bottom',
                                                    'title'             => 'Вверх',
                                                ],
                                            ) ?>
                                        <?php
                                        endif; ?>
                                </div>
                            </div>
                            <?php
                            $i++;
                        endforeach;
                    }
                    catch (JsonException $e) {
                        PrintHelper::exception($layoutId, $e);
                    } ?>
            </div>
        </div>
    </div>

</div>
