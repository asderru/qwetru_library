<?php
    
    use core\edit\entities\Utils\Column;
    use core\edit\entities\Utils\Data;
    use core\edit\entities\Utils\Row;
    use core\edit\entities\Utils\Table;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\helpers\ArrayHelper;
    
    /* @var $this yii\web\View */
    /* @var $model Table */
    /* @var $rows Row[] */
    /* @var $tableDatas Data[] */
    /* @var $columns Column[] */
    
    $layoutId = '#frontend_widgets_views_table';
    $cols     = count($columns);

?>

<div class='table-responsive'>

    <table class='table table-bordered table-striped table-hover table-sm'>
        <caption>
            <?= FormatHelper::asHtml($model->text) ?>
        </caption>

        <thead class='table-light'>
        <td>
        </td>
        <?php
            foreach ($columns as $column): ?>
                <td>
                    <?= Html::encode(
                        $column->name,
                    ) ?>

                </td>
            <?php
            endforeach;
        ?>
        </thead>

        <tbody>
        <?php
            foreach ($rows as $row):
                //первый цикл
                ?>
                <tr>
                    <!--##########################################-->
                    <td>
                        <?= Html::encode(
                            $row->name,
                        ) ?>

                    </td>
                    <!--##########################################-->
                    <?php
                        $i = 1;
                        foreach (
                            $tableDatas
                            
                            as $rowData
                        ):
                            try {
                                if (
                                    ArrayHelper::getValue
                                    (
                                        $rowData, 'rowId',
                                    ) === $row->id
                                ):
                                    ?>
                                    <td>
                                        
                                        <?php
                                            if (ArrayHelper::getValue($rowData, 'value') !== null):
                                                ?>
                                                <?=
                                                ArrayHelper::getValue($rowData, 'value')
                                                ?>
                                            <?php
                                            endif ?>

                                    </td>
                                
                                <?php
                                endif;
                            }
                            catch (Exception $e) {
                                PrintHelper::exception('ArrayHelper ' . $layoutId, $e);
                            }
                            
                            $i++;
                        endforeach;
                        $tableData = array_slice($tableDatas, $cols)
                        //конец второго цикла
                    ?>
                </tr>
            <?php
            endforeach;
        ?>
        </tbody>
    </table>
</div>
