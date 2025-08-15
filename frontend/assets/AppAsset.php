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
                '/vendors/bootstrap-5.3.7/css/bootstrap.min.css',
                'assets/css/meanmenu.min.css',
                'assets/css/style.css',
                'assets/css/dark.css',
                'assets/css/responsive.css',
            ];
        
        public $js
            = [
                'vendors/jquery.min.js',
                'vendors/bootstrap-5.3.7/bootstrap.bundle.min.js',
                'assets/js/meanmenu.min.js',
                'assets/js/wow.min.js',
                'assets/js/custom.js',
            ];
    }
