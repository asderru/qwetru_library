<?php
    
    use core\edit\forms\UploadForm;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use kartik\helpers\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\User\Profile */
    /* @var $uploadForm UploadForm */
    /* @var $actionId string */
    
    $layoutId = '#frontend_cabinet_profile_view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Кабинет', 'url' => ['/cabinet/default/index']];
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class='row'>

    <div class='col-xl-6'>
        <div class='card mb-3'>

            <div class='card-header bg-light'>
                Профиль <strong>
                    <?= $model->name ?>
                </strong>
            </div>
            <div class='card-body'>

                <div class='table-responsive'>
                    <div class='table table-striped'>
                        
                        <?php
                            try {
                                echo DetailView::widget(
                                    [
                                        'model'      => $model,
                                        'attributes' => [
                                            'id',
                                            'first_name',
                                            'last_name',
                                            'name',
                                            [
                                                'attribute' => 'date_of_birth',
                                                'format'    => 'date',
                                            ],
                                            'place',
                                            'country_id',
                                            'sort',
                                            [
                                                'attribute' => 'created_at',
                                                'format'    => 'dateTime',
                                                'label'     => 'Дата создания профиля',
                                            ],
                                            [
                                                'attribute' => 'updated_at',
                                                'format'    => 'dateTime',
                                                'label'     => 'Дата редактирования',
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
        </div>
    </div>
    <div class='col-xl-6'>
        <!--####### Одна картинка #############-->
        
        <?= ($uploadForm)
            ? $this->render(
                '@app/views/layouts/cached/uploadImage',
                [
                    'model'      => $model,
                    'uploadForm' => $uploadForm,
                ],
            )
            :
            $this->render(
                '@app/views/layouts/cached/clearImage',
                [
                    'model' => $model,
                ],
            ) ?>

        <!--####### Конец картинки ######################-->
        <div class='card mb-3'>
            <div class='card-header bg-light'>
                <strong>
                    Жизнеписание
                </strong>
            </div>
            <div class='card-body'>
                <?= FormatHelper::asHtml($model->description) ?>
            </div>
            <div class="card-footer">
                <?= Html::a(
                    'Редактировать',
                    [
                        'update',
                    ],
                    [
                        'class' => 'btn btn-sm btn-outline-dark',
                    ],
                )
                ?>
            </div>
        </div>

    </div>
</div>
