<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Content\Content;
    use core\helpers\FormatHelper;
    use core\helpers\ModelHelper;
    use core\helpers\ParametrHelper;
    use core\read\readModels\Content\ContentReadRepository;
    use core\services\rss\RssItem;
    use core\tools\services\SeoClean;
    use DOMDocument;
    use DOMException;
    use Throwable;
    use Yii;
    use yii\base\Model;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class RssController extends MainController
    {
        private ContentReadRepository $repository;
        
        public function __construct(
            $id,
            $module,
            ContentReadRepository $repository,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
        }
        
        /**
         * @throws Throwable
         */
        public function actionIndex(): string
        {
            $site     = ParametrHelper::getInformation();
            $rssItems = array_map(static function (Content $model) use ($site) {
                return new RssItem(
                    $site->name,
                    $site->url,
                    $site->description,
                    $model->title,
                    (new SeoClean())->getDescription($model),
                    $model->id,
                    $model->updated_at,
                );
            }, $this->repository::getQuery()
                                ->orderBy(
                                    [
                                        'updated_at' => SORT_DESC,
                                    ],
                                )
                                ->limit(100)
                                ->all());
            
            return $schemaData = self::generateRss($rssItems, $site->name, $site->url, $site->description);
        }
        
        /**
         * @throws DOMException
         */
        public function generateRss(array $items, string $siteTitle, string $siteLink, string $siteDescription): ?string
        {
            $dom               = new DOMDocument('1.0', 'utf-8');
            $dom->formatOutput = true;
            
            $rss = $dom->createElement('rss');
            $rss->setAttribute('version', '2.0');
            $dom->appendChild($rss);
            
            $channel = $dom->createElement('channel');
            $rss->appendChild($channel);
            
            $channel->appendChild($dom->createElement('title', htmlspecialchars($siteTitle)));
            $channel->appendChild($dom->createElement('link', $siteLink));
            $channel->appendChild($dom->createElement('description', htmlspecialchars($siteDescription)));
            
            foreach ($items as $item) {
                $itemElement = $dom->createElement('item');
                
                // Добавляем атрибут turbo со значением true к элементу item
                $itemElement->setAttribute('turbo', 'true');
                
                $channel->appendChild($itemElement);
                
                $itemElement->appendChild($dom->createElement('title', htmlspecialchars($item->itemTitle)));
                $itemElement->appendChild($dom->createElement('link', $item->itemLink));
                
                // Используем CDATA для вставки HTML-кода в элемент description
                $itemElement->appendChild($dom->createElement('description', $item->itemDescription));
                
                $itemElement->appendChild($dom->createElement('pubDate', FormatHelper::asDateTime($item->itemUpdate, 'php:Y-m-d\TH:i:s')));
            }
            
            
            return $dom->saveXML();
        }
        
        /**
         * @throws Throwable
         * @throws NotFoundHttpException
         */
        public function actionView(int $id): string|Response
        {
            $model = $this->repository::find($id);
            if (!$model) {
                // Перенаправляем на страницу index
                return $this->redirect(['index']);
            }
            
            $site = ParametrHelper::getInformation();
            
            $parent = ModelHelper::getModel($model->text_type, $model->parent_id);
            
            if (!$parent) {
                throw new NotFoundHttpException('Страница не найдена.');
            }
            // Генерация и возврат Турбо-страницы
            $turboPageContent = $schemaData = self::generateTurboPage($site, $parent);
            
            Yii::$app->response->format = Response::FORMAT_RAW;
            Yii::$app->response->headers->add('Content-Type', 'application/xml');
            return $turboPageContent;
        }
        
        public function getModel(
            int $textType,
            int $id,
        ): Model
        {
            return ModelHelper::getModel($textType, $id);
        }
        
        private function generateTurboPage(Information $site, Model|Content $model): string
        {
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">';
            $content .= '<channel>';
            $content .= '<title>' . htmlspecialchars($site->name) . '</title>';
            $content .= '<link>' . htmlspecialchars($site->getUrl()) . '</link>';
            $content .= '<description>' . htmlspecialchars(FormatHelper::stripTags($site->description)) . '</description>';
            $content .= '<language>ru</language>';
            $content .= '<turbo:analytics></turbo:analytics>';
            $content .= '<turbo:adNetwork></turbo:adNetwork>';
            $content .= '<turbo:extendedHtml>true</turbo:extendedHtml>';
            $content .= '<item turbo="true">';
            $content .= '<link>' . htmlspecialchars($model->getOwnUrl()) . '</link>';
            $content .= '<turbo:source></turbo:source>';
            $content .= '<turbo:topic></turbo:topic>';
            $content .= '<pubDate>' . FormatHelper::asDateTime($model->updated_at, 'php:Y-m-d\TH:i:s') . '</pubDate>';
            $content .= '<author>' . $site->name . '</author>';
            $content .= '<metrics>';
            $content .= '<yandex schema_identifier="Идентификатор">';
            $content .= '<breadcrumblist>';
            $content .= '<breadcrumb url="' . $site->getUrl() . '" text="Главная"/>';
            $content .= '</breadcrumblist>';
            $content .= '</yandex>';
            $content .= '</metrics>';
            $content .= '<yandex:related></yandex:related>';
            $content .= '<turbo:content>';
            $content .= '<header><h1>' . FormatHelper::stripTags($model->title) . '</h1>';
            
            $content .= '<figcaption>' . FormatHelper::stripTags($model->title) . '</figcaption>';
            if ($model->getPictureUrl() !== null) {
                $content .= '<figure>';
                $content .= '<img src="' . $model->getPictureUrl() . '"/>';
                $content .= '</figure>';
            }
            
            $content .= '<figcaption>' . FormatHelper::stripTags($model->title) . '</figcaption>';
            
            $content .= '</header>';
            $content .= '<article>';
            $content .= '<turbo:content><![CDATA[' . $model->text . ']]></turbo:content>';
            $content .= '</article>';
            $content .= '</turbo:content>';
            $content .= '</item>';
            $content .= '</channel>';
            $content .= '</rss>';
            
            return $content;
        }
        
    }
