<?php
    
    use yii\web\View;
    
    
    /* @var $this View */
    /* @var $imgBackground string */
    /* @var $schemaData array */
    /* @var $content string */
    
    $this->beginContent('@app/views/layouts/main.php');
?>

<!--###### Content ##########################################################-->


<?= $this->render(
    '@app/views/layouts/partials/_breadcrumbs',
) ?>


<div class="alert-area">
    <?= $this->render(
        '@app/views/layouts/partials/_messages',
    ) ?>
</div>

<?= $content ?>

<?php
    $this->endContent() ?>
