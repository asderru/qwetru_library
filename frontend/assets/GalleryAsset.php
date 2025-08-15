<?php
    
    namespace frontend\assets;
    
    use yii\web\AssetBundle;
    use yii\web\JqueryAsset;
    
    /**
     * Main frontend application asset bundle.
     */
    class GalleryAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl  = '@web';
        
        public $css
            = [
                'vendors/glightbox/css/glightbox.min.css',
            ];
        
        public $js
            = [
                'vendors/glightbox/js/glightbox.min.js',
            ];
        
        public $depends
            = [
                JqueryAsset::class,
            ];
        
    }
