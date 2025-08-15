#!/bin/bash

# Путь к папке, которую нужно отслеживать
WATCH_DIR="/var/www/server_3/shopnseo_ru/frontend/views"

# URL вашего локального сайта
SITE_URL="http://shopnseo.local/"

# Запускаем browser-sync
browser-sync start --proxy "$SITE_URL" --files "$WATCH_DIR/**/*.php" &

# Отслеживаем изменения PHP-файлов
inotifywait -m -r -e modify,create,delete "$WATCH_DIR" |
while read -r directory events filename; do
    if [[ "$filename" =~ \.php$ ]]; then
        echo "File $filename has been $events"
        # Перезагрузка браузера через browser-sync
        browser-sync reload
    fi
done
