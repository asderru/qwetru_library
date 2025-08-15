<?php
    
    use core\helpers\ClearHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $title string */
    /* @var $this yii\web\View */
    
    $model = (new ParametrHelper)::getSite();
    
    $layoutId = '#frontend_views_partials_header';
    $lgt      = null;
    $ltd      = null;
    
    try {
        $lgt = $model->contact->getLgt();
    }
    catch (Exception $e) {
        Yii::$app->errorHandler->logException($e);
        PrintHelper::saveDbDummy($layoutId, $e);
    }
    try {
        $ltd = $model->contact->getLtd();
    }
    catch (Exception $e) {
        Yii::$app->errorHandler->logException($e);
        PrintHelper::saveDbDummy($layoutId, $e);
        PrintHelper::exception('header', $e);
    }

?><!-- Control the behavior of search engine crawling and indexing -->
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="referrer" content="origin-when-crossorigin">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<?= Html::csrfMetaTags()
?>
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
<meta name="google" content="notranslate">
<meta
        name="author"
        content="<?= ClearHelper::pregReplace($model->name)
        ?>"
>
<meta
        name="copyright"
        content="(c)<?= date('Y')
        ?>"
>
<meta
        name="ICBM"
        content="<?= ClearHelper::pregReplace($model?->title)
        ?>"
>
<meta name="geo.region" content="RU-Москва">
<meta name="geo.placename" content="Москва/Россия">
<meta
        name="geo.position"
        content="<?= $lgt ?>, <?= $ltd ?>"
>
<meta
        name="ICBM"
        content="<?= $lgt ?>, <?= $ltd ?>"
>
<!--Links Prefetch-->
<link rel='preconnect' href='https://cdn.jsdelivr.net' crossorigin>
