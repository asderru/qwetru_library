<?php
    
    use core\helpers\FormatHelper;
    use yii\base\Model;
    use yii\helpers\Html;
    
    /* @var $model Model */
    /* @var $url string */
?>
<!-- Start Single Product -->
<div class='col-md-6 col-lg-4 col-sm-12'>
    <div class='post__itam'>
        <div class='content'>
            <h3><a href='blog-details.html'>
                    <?= Html::encode($model->title) ?> </a></h3>
            <?= FormatHelper::asDescription($model, 10) ?>
            <div class='post__time'>
                                <span class='day'>
        <?= FormatHelper::asDate($model->created_at) ?></span>
            </div>
        </div>
    </div>
</div>
