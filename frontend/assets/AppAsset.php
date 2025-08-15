<?php
    
    namespace frontend\assets;
    
    use yii\web\AssetBundle;
    
    /**
     * Main frontend application asset bundle.
     * family=Montserrat&
     * family=Open+Sans:wght@300;400;500&
     * family=Oswald:wght@300;400
     */
    class AppAsset extends AssetBundle
    {
        public $baseUrl;
        public $basePath;
        
        public $css
            = [
                'css/main.min.css',
            ];
        
        public $js
            = [
                'vendors/jquery.min.js',
                'vendors/bootstrap-5.3.7/bootstrap.bundle.min.js',
                '/assets/js/plugins.js',
                '/assets/js/script.js',
            ];
    }
