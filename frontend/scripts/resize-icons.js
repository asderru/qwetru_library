import sharp from 'sharp';
import path from 'path';
import {fileURLToPath} from 'url';

// Получаем путь к текущему файлу
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Путь к исходному файлу
const inputPath = path.resolve(__dirname, '../web/fav/icon-512x512.png');

// Масштабы и пути для новых изображений
const sizes = [{size: 36, output: '../web/fav/icon-36x36.png'}, {
    size: 48,
    output: '../web/fav/icon-48x48.png'
}, {size: 72, output: '../web/fav/icon-72x72.png'}, {size: 96, output: '../web/fav/icon-96x96.png'}, {
    size: 128,
    output: '../web/fav/icon-128x128.png'
}, {size: 144, output: '../web/fav/icon-144x144.png'}, {size: 192, output: '../web/fav/icon-192x192.png'}, {
    size: 256,
    output: '../web/fav/icon-256x256.png'
}, {size: 70, output: '../web/fav/ms-icon-70x70.png'},     // <square70x70logo src="/fav/ms-icon-70x70.png"/>
    {size: 150, output: '../web/fav/ms-icon-150x150.png'},  // <square150x150logo src="/fav/ms-icon-150x150.png"/>
    {size: 310, output: '../web/fav/ms-icon-310x310.png'},  // <square310x310logo src="/fav/ms-icon-310x310.png"/>
    {size: 144, output: '../web/fav/ms-icon-144x144.png'}];

// Функция для создания изображений с различными размерами
sizes.forEach(({size, output}) => {
    sharp(inputPath)
        .resize(size, size)
        .toFile(path.resolve(__dirname, output), (err, info) => {
            if (err) {
                console.error(`Ошибка при создании ${output}:`, err);
            } else {
                console.log(`Создан файл ${output}:`, info);
            }
        });
});
