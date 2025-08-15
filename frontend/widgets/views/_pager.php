<?php
    
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $nextUrl string */
    /* @var $prevUrl string */
    /* @var $next Model */
    /* @var $prev Model */
    
    $layoutId = '#frontend_widgets_views_pager';

?>

<div class='col-sm-6  prev-section'>
    
    <?= $prevUrl ?>

</div>

<div class='col-sm-6  next-section'>
    
    <?= $nextUrl ?>

</div>
