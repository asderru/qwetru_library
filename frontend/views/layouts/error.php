<?php
    
    use frontend\assets\AppAsset;
    use yii\web\View;
    
    AppAsset::register($this);
    
    /* @var $this View */
    /* @var $content string */
    /* @var $content string */
    
    $this->beginContent('@app/views/layouts/main.php');
    
    $layoutId = '#layouts_error';

?>

<!--###### Navigator #######################################################-->

<?= $this->render(
    '@app/views/layouts/partials/_navigator',
) ?>


<!--###### Content ##########################################################-->


<?= $content ?>

<?php
    $this->endContent() ?>
