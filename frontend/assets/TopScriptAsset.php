<?php
    
    namespace frontend\assets;
    
    use yii\web\AssetBundle;
    use yii\web\View;
    
    /**
     * Main backend application asset bundle.
     */
    class TopScriptAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl  = '@web';
        
        public $js
            = [
                '/js/top-script.js',
            ];
        
        public $jsOptions
            = [
                'position' => View::POS_HEAD,
            ];
    }
