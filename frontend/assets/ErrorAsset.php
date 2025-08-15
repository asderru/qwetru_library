<?php
    
    namespace frontend\assets;
    
    use yii\web\AssetBundle;
    
    /**
     * Main frontend application asset bundle.
     * family=Montserrat&
     * family=Open+Sans:wght@300;400;500&
     * family=Oswald:wght@300;400
     */
    class ErrorAsset extends AssetBundle
    {
        public $baseUrl  = '@homepage';
        public $basePath = '@frontRoot';
        
        public $css
            = [
                'css/main.min.css',
            ];
        
        public $js
            = [
                'js/main.js',
            ];
        
    }
