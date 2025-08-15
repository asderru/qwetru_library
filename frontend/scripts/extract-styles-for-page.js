import {readdir, readFile, writeFile} from 'fs/promises';
import {load} from 'cheerio'; // Используем load вместо cheerio
import postcss from 'postcss';
import path, {join} from 'path';

const layoutsDir = '/var/www/server_3/shopnseo_ru/frontend/src/views/layouts';
const cssFilePath = '/var/www/server_3/shopnseo_ru/frontend/web/css/main.css';

async function extractStylesForPage() {
    try {
        // Получаем список всех HTML файлов в layoutsDir
        const files = await readdir(layoutsDir);
        const htmlFilePaths = files
            .filter(file => file.endsWith('.html'))
            .map(file => join(layoutsDir, file));

        console.log(`Found ${htmlFilePaths.length} HTML files to process`);

        // Читаем основной CSS файл
        const cssContent = await readFile(cssFilePath, 'utf8');

        for (const htmlFilePath of htmlFilePaths) {
            const htmlContent = await readFile(htmlFilePath, 'utf8');

            // Парсим HTML с помощью cheerio
            const $ = load(htmlContent); // Используем load вместо cheerio

            // Собираем все селекторы, которые используются на странице
            const selectors = new Set();
            $('*').each((index, element) => {
                const tagName = element.tagName.toLowerCase(); // Получаем имя тега в нижнем регистре
                selectors.add(tagName); // Добавляем тег как селектор (например, body, header, article и т.д.)

                const classNames = $(element).attr('class');
                const id = $(element).attr('id');
                if (classNames) {
                    classNames.split(/\s+/).forEach(className => selectors.add(`.${className}`));
                }
                if (id) {
                    selectors.add(`#${id}`);
                }
                // Дополнительно можно добавить другие типы атрибутов или псевдоклассы, если нужно
            });

            console.log(`Found ${selectors.size} selectors in ${htmlFilePath}`);

            // Используем postcss для извлечения стилей, соответствующих выбранным селекторам
            const root = postcss.parse(cssContent);
            const filteredCss = root.nodes.filter(node => {
                if (node.type !== 'rule') return false;
                const ruleSelectors = node.selector.split(/\s*,\s*/);
                return ruleSelectors.some(selector => selectors.has(selector));
            });

            // Преобразуем отфильтрованные правила обратно в CSS
            const filteredCssContent = filteredCss.map(node => node.toString()).join('\n');

            // Генерируем путь для сохранения CSS, заменяя расширение HTML на CSS
            const outputCssFilePath = path.join('/var/www/server_3/shopnseo_ru/frontend/web/styles',
                path.basename(htmlFilePath, '.html') + '.css');

            // Сохраняем отфильтрованные стили в новый файл
            await writeFile(outputCssFilePath, filteredCssContent);

            console.log(`Filtered CSS for ${htmlFilePath} saved to ${outputCssFilePath}`);
        }
    } catch (error) {
        console.error('Error during style extraction:', error);
        if (error.stack) {
            console.error('Stack trace:', error.stack);
        }
        process.exit(1);
    }
}

extractStylesForPage();
