<?php
    
    namespace frontend\assets;
    
    use core\tools\Constant;
    use DOMDocument;
    use Yii;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    
    class Bundles
    {
        
        /**
         * @throws InvalidConfigException
         */
        public static function getPreloadedImagesFromUrl(?string $url, int $textType): void
        {
            $preloadTags = null;
            
            $preloadStyles = self::getPreloadedStyles($textType);
            if ($url !== null) {
                $preloadTags = self::generateImagePreloadTags($url);
            }
            
            Yii::$app->view->blocks['preloadTags'] = $preloadStyles . $preloadTags;
        }
        
        /**
         * @throws InvalidConfigException
         */
        public static function setPreloadedTags(int $textType, ?string $picture): void
        {
            // Получаем прелоад-стили, или пустую строку если их нет
            $preloadStyles = self::getPreloadedStyles($textType) ?? '';
            
            $preloadTags = '';
            
            if (!empty($picture)) {
                $dom = new DOMDocument();
                
                libxml_use_internal_errors(true);
                try {
                    $dom->loadHTML($picture, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                }
                finally {
                    libxml_clear_errors();
                }
                
                $tagsList = [];
                
                // Обработка source элементов
                $sources      = $dom->getElementsByTagName('source');
                $mainImageUrl = null;
                $mainType     = null;
                $mainSrcset   = null;
                $mainSizes    = null;
                
                foreach ($sources as $source) {
                    $type   = $source->getAttribute('type');
                    $srcset = $source->getAttribute('srcset');
                    $sizes  = $source->getAttribute('sizes');
                    
                    // Берём первый вариант как основной
                    if (!$mainImageUrl) {
                        $mainType   = $type;
                        $mainSrcset = $srcset;
                        $mainSizes  = $sizes;
                        
                        // Берём самый большой вариант из srcset
                        $srcsetParts = array_map('trim', explode(',', $srcset));
                        if (!empty($srcsetParts)) {
                            $largestImage = array_pop($srcsetParts); // Последний элемент - самый большой
                            $mainImageUrl = explode(' ', $largestImage)[0] ?? null;
                        }
                    }
                }
                
                // Обработка img элемента (если source нет)
                $img = $dom->getElementsByTagName('img')->item(0);
                if ($img && !$mainImageUrl) {
                    $mainImageUrl = $img->getAttribute('src');
                    $mainSrcset   = $img->getAttribute('srcset') ?: null;
                    $mainSizes    = $img->getAttribute('sizes') ?: null;
                }
                
                // Создаём только один preload-тег для самого большого изображения
                if ($mainImageUrl) {
                    $tagsList[] = sprintf(
                        '<link rel="preload" as="image" %s href="%s" %s %s>',
                        $mainType ? 'type="' . htmlspecialchars($mainType, ENT_QUOTES) . '"' : '',
                        htmlspecialchars($mainImageUrl, ENT_QUOTES),
                        $mainSrcset ? 'imagesrcset="' . htmlspecialchars($mainSrcset, ENT_QUOTES) . '"' : '',
                        $mainSizes ? 'imagesizes="' . htmlspecialchars($mainSizes, ENT_QUOTES) . '"' : '',
                    );
                }
                
                $preloadTags = implode("\n", $tagsList);
            }
            
            // Устанавливаем блок прелоад-тегов только если есть стили или картинка
            if ($preloadStyles !== '' || $preloadTags !== '') {
                Yii::$app->view->blocks['preloadTags'] = $preloadStyles . $preloadTags;
            }
        }
        
        /**
         */
        public static function setPreloadedStyle(int $textType): void
        {
            // Получаем прелоад-стили, или пустую строку если их нет
            $preloadStyles = self::getPreloadedStyles($textType) ?? '';
            
            // Устанавливаем блок прелоад-тегов только если есть стили или картинка
            if ($preloadStyles !== '') {
                Yii::$app->view->blocks['preloadTags'] = $preloadStyles;
            }
        }
        
        public static function getPreloadedStyles(int $textType): string
        {
            $critical = self::getCriticalStyle($textType);
            
            $criticalStylePreloadTag = self::getCriticalStylePreloadTag($critical);
            
            // Возвращаем строку с прелоад-тегами
            return trim($criticalStylePreloadTag);
        }
        
        public static function getFontPreloadStyles(): string
        {
            // Определяем путь к нужному стилю в зависимости от типа текста
            return "<link rel='preload' href='"
                   . Yii::$app->request->baseUrl
                   . "/vendors/fonts/russo-one.css' as='font' type='font/woff2' crossorigin='anonymous'>";
        }
        
        /**
         */
        public static function generatePicturePreloadTags(string $picture): string
        {
            // Извлекаем все source и img теги с помощью регулярных выражений
            preg_match('/<source[^>]*srcset="([^"]*)"/', $picture, $sourceMatches);
            preg_match('/<img[^>]*srcset="([^"]*)"/', $picture, $imgMatches);
            
            if (empty($sourceMatches) && empty($imgMatches)) {
                return '';
            }
            
            $tags = [];
            
            // Обрабатываем source с WebP
            if (!empty($sourceMatches[1])) {
                $srcset      = $sourceMatches[1];
                $mainWebpUrl = '';
                
                // Находим URL с максимальным размером (1320w)
                if (preg_match('/(\S+)\s+1320w/', $srcset, $mainUrlMatch)) {
                    $mainWebpUrl = $mainUrlMatch[1];
                }
                
                if ($mainWebpUrl) {
                    $tags[] = '<link rel="preload" as="image" ' .
                              'href="' . Html::encode($mainWebpUrl) . '" ' .
                              'imagesrcset="' . Html::encode($srcset) . '" ' .
                              'imagesizes="(max-width: 1320px) 100vw, 1320px" ' .  // Добавлено imagesizes
                              'type="image/webp" ' .
                              'fetchpriority="high">';
                }
            }
            
            // Обрабатываем img с JPG/PNG
            if (!empty($imgMatches[1])) {
                $srcset       = $imgMatches[1];
                $mainImageUrl = '';
                
                // Находим URL с максимальным размером (1320w)
                if (preg_match('/(\S+)\s+1320w/', $srcset, $mainUrlMatch)) {
                    $mainImageUrl = $mainUrlMatch[1];
                }
                
                if ($mainImageUrl) {
                    $tags[] = '<link rel="preload" as="image" ' .
                              'href="' . Html::encode($mainImageUrl) . '" ' .
                              'imagesrcset="' . Html::encode($srcset) . '" ' .
                              'imagesizes="(max-width: 1320px) 100vw, 1320px" ' .  // Добавлено imagesizes
                              'fetchpriority="high">';
                }
            }
            
            return implode(PHP_EOL, $tags) . PHP_EOL;
        }
        
        /**
         */
        public static function generateImagePreloadTags(string $url): string
        {
            $sizes = [
                3  => 330,   // 330w
                6  => 660,   // 660w
                12 => 1200, // 1200w
            ];
            
            // Генерируем srcset строку
            $srcsetParts = [];
            foreach ($sizes as $multiplier => $width) {
                $currentUrl    = self::modifyImageUrl($url, $multiplier);
                $srcsetParts[] = Html::encode($currentUrl) . ' ' . $width . 'w';
            }
            $srcset = implode(', ', $srcsetParts);
            
            // Используем максимальный размер как основной href
            $mainImageUrl = self::modifyImageUrl($url, 12);
            
            // Формируем тег предзагрузки
            return '<link rel="preload" as="image" ' .
                   'href="' . Html::encode($mainImageUrl) . '" ' .
                   'imagesrcset="' . $srcset . '" ' .
                   'fetchpriority="high">' . PHP_EOL;
        }
        
        public static function getMainPictureUrl(?string $picture = null): string
        {
            if (!$picture) {
                return '';
            }
            // Ищем source с WebP изображением и максимальным размером (1320w)
            if (preg_match('/srcset="([^"]*?_col-12\.webp\s+1320w[^"]*)"/', $picture, $matches)) {
                // Извлекаем конкретный URL для 1320w
                if (preg_match('/(\S+_col-12\.webp)\s+1320w/', $matches[1], $urlMatch)) {
                    return $urlMatch[1];
                }
            }
            
            // Запасной вариант - ищем JPG изображение если WebP не найден
            if (preg_match('/src="([^"]*?_col-12\.jpg)"/', $picture, $matches)) {
                return $matches[1];
            }
            
            return '';
        }
        
        private static function getCriticalStyle(int $textType): string
        {
            
            return match ($textType) {
                Constant::SITE_TYPE => self::getMainStyle(),
                default             => self::getPageStyle(),
            };
        }
        
        private static function getCriticalStylePreloadTag(string $styleUrl): string
        {
            return "<link rel='preload' href='"
                   . Yii::$app->request->baseUrl
                   . $styleUrl
                   . "' as='style' onload=\"this.rel='stylesheet'\">";
        }
        
        private static function getMainStyle(): string
        {
            return '/css/critical-index.min.css';
        }
        
        private static function getBookStyle(): string
        {
            return '/css/critical-book.min.css';
        }
        
        private static function getCabinetStyle(): string
        {
            return '/css/critical-cabinet.min.css';
        }
        
        /* ###### PreloadedImages ########################################################################*/
        
        private static function getContactStyle(): string
        {
            return '/css/critical-contact.min.css';
        }
        
        private static function getPageStyle(): string
        {
            return '/css/critical-page.css';
        }
        
        private static function getAuthStyle(): string
        {
            return '/css/critical-auth.min.css';
        }
        
        private static function modifyImageUrl(string $url, int $size): string
        {
            $parsedUrl = parse_url($url);
            $path      = $parsedUrl['path'];
            
            // Изменяем размер в URL
            $newPath = preg_replace('/col-\d+/', "col-$size", $path);
            
            // Изменяем расширение на .webp для размера 6
            if ($size === 6) {
                $newPath = preg_replace('/\.(jpg|jpeg|png)$/', '.webp', $newPath);
            }
            
            // Собираем URL обратно
            return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $newPath;
        }
    }
