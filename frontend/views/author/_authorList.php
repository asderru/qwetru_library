<?php
    
    use core\edit\entities\Library\Book;
    
    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\DataProviderInterface */
    /* @var $book Book */

?>

<!--##########################################################################################################-->
<div class="row row-cols-1">
    <?php
        foreach ($dataProvider->getModels() as $book): ?>
            <?php
            if ($book->status === 1): ?>
                <?= $this->render('_book', [
                    'book' => $book,
                ]) ?>
            <?php
            endif; ?>
        <?php
        endforeach; ?>
</div>
<!--##########################################################################################################-->
