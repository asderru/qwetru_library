#!/bin/bash

# Путь к файлу с URL-ами
URLS_FILE="/var/www/server_3/shopnseo_ru/frontend/scripts/urls.txt"

# Временный файл для хранения HTML
TEMP_FILE="/var/www/server_3/shopnseo_ru/frontend/tmp/temp_html_file.html"

# Целевой файл для сохранения HTML
OUTPUT_FILE="/var/www/server_3/shopnseo_ru/frontend/web/index.html"

# Путь к скрипту для создания критического стиля
CRITICAL_STYLE_SCRIPT="/var/www/server_3/shopnseo_ru/frontend/scripts/create-critical-style.js"

# Прочитать файл с URL-ами построчно
while IFS= read -r URL
do
  echo "Processing $URL -> $OUTPUT_FILE"

  # Загружаем HTML страницу с помощью wget и проверяем статус
  wget -S -O "$TEMP_FILE" "$URL"
  WGET_STATUS=$?

  if [[ $WGET_STATUS -ne 0 ]]; then
    echo "Ошибка: не удалось загрузить HTML страницу для $URL. Код состояния wget: $WGET_STATUS"
    continue
  fi

  # Проверяем, что файл был успешно загружен и не пустой
  if [[ -s "$TEMP_FILE" ]]; then
    # Копируем содержимое временного файла в целевой файл
    sudo cp "$TEMP_FILE" "$OUTPUT_FILE"
    echo "HTML код страницы успешно сохранён в $OUTPUT_FILE"

    # Переходим в директорию /var/www/server_3/shopnseo_ru/frontend
    cd /var/www/server_3/shopnseo_ru/frontend

    # Выполняем скрипт для создания критического стиля
    sudo node "$CRITICAL_STYLE_SCRIPT" "$OUTPUT_FILE"
    echo "Критический стиль создан для $OUTPUT_FILE"
  else
    echo "Ошибка: файл пуст после загрузки $URL."
    echo "Содержимое временного файла:"
    cat "$TEMP_FILE"
  fi

done < "$URLS_FILE"

# Удаляем временный файл
rm -f "$TEMP_FILE"
