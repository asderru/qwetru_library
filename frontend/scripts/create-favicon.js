import sharp from 'sharp';
import pngToIco from 'png-to-ico';
import fs from 'fs';
import path from 'path';
import {fileURLToPath} from 'url';

// Получаем путь к текущему файлу
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Путь к исходному файлу
const inputPath = path.resolve(__dirname, '../web/fav/icon-512x512.png');

// Масштабы и пути для новых изображений
const sizes = [
    {size: 16, output: '../web/fav/icon-16x16.png'},
    {size: 24, output: '../web/fav/icon-24x24.png'},
    {size: 32, output: '../web/fav/icon-32x32.png'},
    {size: 48, output: '../web/fav/icon-48x48.png'},
    {size: 64, output: '../web/fav/icon-64x64.png'},
];

// Создание PNG файлов с различными размерами
Promise.all(
    sizes.map(({size, output}) =>
        sharp(inputPath)
            .resize(size, size)
            .toFile(path.resolve(__dirname, output))
    )
).then(() => {
    // После создания PNG файлов, используем их для создания favicon.ico
    const pngPaths = sizes.map(({output}) => path.resolve(__dirname, output));
    pngToIco(pngPaths)
        .then(buf => {
            const outputPath = path.resolve(__dirname, '../web/favicon.ico');
            fs.writeFileSync(outputPath, buf);
            console.log('Создан файл favicon.ico');
        })
        .catch(err => {
            console.error('Ошибка при создании favicon.ico:', err);
        });
}).catch(err => {
    console.error('Ошибка при создании PNG файлов:', err);
});
