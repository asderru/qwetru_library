<?php
    
    use yii\web\View;
    
    /* @var $this View */
    /* @var $content string */

?>

<?php
    $this->beginContent('@frontend/views/layouts/blank.php') ?>

<div class="container">

    <div class='row inner-area__content'>

        <div class="col-lg-9">
            <!--#################################################################-->
            <?= $content ?>
        </div>

        <aside class='col-lg-3 main-aside' itemscope itemtype='http://schema.org/WPSideBar'>
            
            <?=
                $this->render(
                    '@app/views/layouts/cached/_user-aside',
                )
            ?>
        </aside>

    </div>

</div>
    <?php
        $this->endContent() ?>
