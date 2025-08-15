<?php
    
    namespace frontend\widgets;
    
    use core\edit\forms\User\FeedbackForm;
    use yii\bootstrap5\Widget;
    
    class FeedbackWidget extends Widget
    {
        public array $model;
        
        public function run(): string
        {
            
            $feedbackForm = new FeedbackForm();
            
            return $this->render(
                '_feedback',
                [
                    'feedbackForm' => $feedbackForm,
                    'model'        => $this->model,
                ],
            );
            
        }
    }
