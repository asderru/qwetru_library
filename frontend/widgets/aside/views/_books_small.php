<?php
    
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $books core\edit\entities\Library\Book[] */
    /* @var $book core\edit\entities\Library\Book */
    
    $layoutId = '#frontend_widgets_aside_views_books_small';

?>
<?php
    foreach (
        $books
        
        as $model
    ):
        ?>
        <li>
            <?= Html::a(
                Html::encode(
                    $model->name,
                ),
                Url::to($model['link'], true),
                [
                    'class'      => 'body-title__link',
                    'aria-label' => $model->name,
                ],
            )
            ?>

        </li>
    
    <?php
    endforeach; ?>
