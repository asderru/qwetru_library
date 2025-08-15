<?php
use frontend\assets\AppAsset;use yii\bootstrap5\Html;
/* @var $this yii\web\View */
/* @var $content string */
AppAsset::register($this);
$schemaData = $this->params['schemaData'] ?? null;
$breadcrumbsData = $this->params['breadcrumbsData'] ?? null;
$this->beginPage() ?><!DOCTYPE html>
<html dir='ltr' lang='<?= Yii::$app->language ?>'>
<head>
    <!-- Settings -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='initial-scale=1.0, width=device-width, shrink-to-fit=no'>
    <meta name='x-dns-prefetch-control' content='on'>
    <link rel='preconnect' href='<?= Yii::getAlias('@url') ?>' crossorigin>
    <link rel='preconnect' href='<?= Yii::getAlias('@static') ?>' crossorigin>
    <?php
        if (isset($this->blocks['preloadTags'])): ?><?= $this->blocks['preloadTags'] ?><?php
        endif; ?>
    <?php
        $this->head() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php
        if ($schemaData): ?>
            <script type='application/ld+json'>
                <?= json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
        <?php
        endif; ?>
    <?php
        if ($breadcrumbsData): ?>
            <script type='application/ld+json'>
                <?= json_encode($breadcrumbsData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
        <?php
        endif; ?>
</head>
<body>

<!-- content starts -->

<?php
    $this->beginBody() ?>

<!--###### header #######################################################-->

<?= $this->render(
    '@app/views/layouts/partials/_navigator',
) ?>

<?= $content ?>

<?php
    echo $this->render('@app/views/layouts/partials/_footer.php');
?>

<!-- content ends -->


<?php
    $this->endBody() ?>

</body>
</html>
<?php
    $this->endPage();
