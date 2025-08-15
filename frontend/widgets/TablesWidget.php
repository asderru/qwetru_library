<?php
    
    namespace frontend\widgets;
    
    use core\edit\assignments\TableAssignment;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\Widget;
    
    class TablesWidget extends Widget
    {
        public Model $model;
        
        public function run(): string
        {
            $parent = $this->model;
            
            $tablesAsses = (new TableAssignment)
                ->getTablesByParent(
                    $parent->site_id,
                    $parent->textType,
                    $parent->id,
                )
                ->andWhere(
                    [
                        '>',
                        'status',
                        Constant::STATUS_ARCHIVE,
                    ],
                )
                ->sorted()
                ->all()
            ;
            
            return $this->render(
                '@app/widgets/views/_tables',
                [
                    'tablesAsses' => $tablesAsses,
                ],
            );
        }
    }
