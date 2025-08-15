<?php
    
    namespace frontend\assets;
    
    use core\tools\Constant;
    
    class PreloadService
    {
        public static function getPreloadedStyle(int $textType): string
        {
            return match ($textType) {
                Constant::SITE_TYPE    => 'css/critical-index.min.css',
                default => ''
            };
            
        }
        
        public static function getPreloadedFonts(): array
        {
            return [
                '/vendors/fonts/RussoOneCyr.woff2',
            ];
            
        }
        
        public static function getColorScheme(?int $color = null): string
        {
            return match ($color) {
                1       => '#000000',
                default => '#ffffff'
            };
            
        }
    }
