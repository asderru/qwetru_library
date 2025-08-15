<?php

use yii\bootstrap5\Html;

/* @var $this yii\web\View */
/* @var $content string */

$this->beginPage() ?><!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="<?= Yii::$app->language ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="<?= Yii::$app->language ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir='ltr' lang='ru-RU' prefix='og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# schema: https://schema.org/'>

<!--<![endif]-->
<head>

    <!-- Settings -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta
            name='viewport'
            content='initial-scale=1.0, width=device-width, shrink-to-fit=no'
    >
    <meta name='x-dns-prefetch-control' content='on'>
    
    <?php
        if (isset($this->params['preload']) && $this->params['preload']) {
            echo $this->render('@app/views/layouts/partials/_preload', [
                'preload' => $this->params['preload'],
            ]);
        }
    ?>
    
    
    <?=
        $this->render(
            '@app/views/layouts/partials/_header_top',
        )
    ?>

    <!-- Search Engines -->
    
    <?php
        $this->head() ?>

    <!-- // Search Engines -->

    <title><?= Html::encode($this->title) ?></title>
    
    <?=
        $this->render(
            '@app/views/layouts/partials/_header_down',
        )
    ?>
</head>

<body>
<?php
    $this->beginBody() ?>

<div class='content-area container'>
    
    
    <?= $content ?>

</div>
<?php
    $this->endBody() ?>

</body>
</html>
<?php
    $this->endPage();
