import {readdir, readFile, writeFile} from 'fs/promises';
import path from 'path';
import postcss from 'postcss';
import CleanCSS from 'clean-css';
import discardDuplicates from 'postcss-discard-duplicates';

// Папка с CSS файлами
const stylesDirectory = '/var/www/server_3/shopnseo_ru/frontend/web/styles/';
const outputCssPath = path.join(stylesDirectory, 'style.css');
const outputMinCssPath = path.join(stylesDirectory, 'style.min.css');

async function combineStyles() {
    try {
        // Читаем все файлы в папке styles
        const files = await readdir(stylesDirectory);

        // Отбираем только CSS файлы
        const cssFiles = files.filter(file => file.endsWith('.css'));
        console.log(`Found ${cssFiles.length} CSS files`);

        let combinedCss = '';

        // Читаем каждый файл и добавляем его содержимое в combinedCss
        for (const file of cssFiles) {
            const filePath = path.join(stylesDirectory, file);
            const cssContent = await readFile(filePath, 'utf8');
            combinedCss += cssContent + '\n'; // Добавляем стили из файла
        }

        console.log('All CSS files combined');

        // Обрабатываем с помощью postcss и удаляем дубликаты
        const root = postcss.parse(combinedCss);

        // Используем discardDuplicates для удаления дубликатов
        const processedCss = await postcss([discardDuplicates()]).process(root, {from: undefined});

        // Сохраняем объединенные стили в style.css
        await writeFile(outputCssPath, processedCss.css);
        console.log(`Combined CSS saved to ${outputCssPath}`);

        // Минифицируем CSS с помощью CleanCSS
        const minified = new CleanCSS().minify(processedCss.css);

        // Сохраняем минифицированную версию в style.min.css
        await writeFile(outputMinCssPath, minified.styles);
        console.log(`Minified CSS saved to ${outputMinCssPath}`);

    } catch (error) {
        console.error('Error during style combination:', error);
        if (error.stack) {
            console.error('Stack trace:', error.stack);
        }
        process.exit(1);
    }
}

combineStyles();
