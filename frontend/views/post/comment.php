<?php
    
    /* @var $this yii\web\View */
    /* @var $content Content */
    /* @var $post Post */
    
    /* @var $model CommentForm */
    
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Content;
    use core\edit\forms\Content\CommentForm;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    
    $layoutId = '#frontend_views_post_comment';
    
    $request = Yii::$app->request;
    $fullUrl = $request->absoluteUrl;
    
    $this->title = $post->name;
    $category    = $post->category;
    
    $this->params['breadcrumbs'][] = [
        'label' => $category->name,
        'url'   => [
            'category/view',
            'id' => $category->id,
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $post->name,
        'url'   => [
            'view',
            'id' => $post->id,
        ],
    ];
    
    $this->params['breadcrumbs'] [] = 'Комментарий';
    
    $this->params['preload'] = $post->getWebPhotoColumn(12);
    
    $this->params['active_category'] = $post->category;
?>

<h1><?= Html::encode($post->title) ?></h1>

<?php
    $form = ActiveForm::begin([
        'action' => ['comment', 'id' => $content->id],
    ]); ?>


<?= Html::activeHiddenInput(
    $model, 'parentId',
    [
        'value' => 1,
    ],
) ?>
<?= $form->field($model, 'text')->textarea(['rows' => 5]) ?>

<div class="form-group">
    <?= Html::submitButton('Отправить комментарий', ['class' => 'btn btn-primary']) ?>
</div>

<?php
    ActiveForm::end(); ?>
