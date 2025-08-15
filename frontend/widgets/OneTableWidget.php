<?php
    
    namespace frontend\widgets;
    
    use core\edit\entities\Utils\Column;
    use core\edit\entities\Utils\Row;
    use core\edit\entities\Utils\Table;
    use yii\bootstrap5\Widget;
    
    class OneTableWidget extends Widget
    {
        public Table $table;
        
        public function run(): string
        {
            $model = $this->table;
            
            $tableDatas = $model->getTableDatas();
            $rows       = (new Row)::getRowsByTable($model->id)->all();
            $columns    = (new Column)::getColumnsByTable($model->id)->all();
            
            return $this->render(
                '@app/widgets/views/_oneTable',
                [
                    'model'      => $model,
                    'tableDatas' => $tableDatas,
                    'rows'       => $rows,
                    'columns'    => $columns,
                ],
            
            );
            
        }
    }
