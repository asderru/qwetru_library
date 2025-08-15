<?php
    
    use core\edit\assignments\TableAssignment;
    use core\edit\entities\Utils\Table;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use frontend\widgets\OneTableWidget;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $tablesAsses TableAssignment */
    /* @var $form ActiveForm */
    
    $layoutId = '#widgets_views_tableAssigned';

?>

<?php
    foreach ($tablesAsses as $tablesAss) {
        
        $table = Table::findOne($tablesAss->table_id);
        
        ?>

        <h5 class="text-center">
            <?=
                Html::encode($tablesAss->name)
            ?>
        </h5>
        <?php
        try {
            echo
            OneTableWidget::widget(
                [
                    'table' => $table,
                ],
            );
        }
        catch (Exception|Throwable $e) {
            PrintHelper::exception('TableWidget ' . $layoutId, $e);
        }
        ?>
        <?= FormatHelper::asHtml($tablesAss->description) ?>
        <hr>
        
        <?php
    } ?>
