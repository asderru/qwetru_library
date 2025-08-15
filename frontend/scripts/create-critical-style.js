import fs from 'fs/promises';
import {PurgeCSS} from 'purgecss';
import postcss from 'postcss';
import cssnano from 'cssnano';
import discardDuplicates from 'postcss-discard-duplicates';
import fetch from 'node-fetch';

async function processCss() {
    try {
        // Чтение списка URL-адресов
        const urls = await fs.readFile('scripts/urls.txt', 'utf8');
        const urlList = urls.split('\n').filter(url => url.trim() !== '');

        // Чтение содержимого main.css
        const mainCss = await fs.readFile('web/css/main.css', 'utf8');

        let combinedPurgedCss = '';

        // Обработка каждого URL
        for (const url of urlList) {
            console.log(`Обработка ${url}`);
            // Загрузка HTML-контента
            const response = await fetch(url);
            const htmlContent = await response.text();

            // Применение PurgeCSS
            const purgeCSSResult = await new PurgeCSS().purge({
                content: [{raw: htmlContent, extension: 'html'}],
                css: [{raw: mainCss}],
                safelist: ['html', 'body'], // Добавьте сюда классы или селекторы, которые всегда нужно сохранять
            });

            combinedPurgedCss += purgeCSSResult[0].css + '\n';
        }

        // Функция для обработки CSS без минификации
        async function processCssContentWithoutMinification(cssContent) {
            const result = await postcss([
                discardDuplicates()
            ]).process(cssContent, {from: undefined});
            return result.css;
        }

        // Функция для обработки CSS с минификацией
        async function processCssContentWithMinification(cssContent) {
            const result = await postcss([
                discardDuplicates(),
                cssnano({
                    preset: ['default', {
                        discardDuplicates: true,
                        mergeRules: true,
                        mergeIdents: true,
                        reduceIdents: true,
                        zindex: false
                    }]
                })
            ]).process(cssContent, {from: undefined});
            return result.css;
        }

        // Обработка combinedPurgedCss без минификации
        const processedPurgedCss = await processCssContentWithoutMinification(combinedPurgedCss);

        // Запись обработанных очищенных стилей в critical.css
        await fs.writeFile('web/css/critical.css', processedPurgedCss);
        console.log('Файл critical.css успешно создан');

        // Чтение содержимого critical-page.css
        const criticalAppCss = await fs.readFile('web/css/critical-page.css', 'utf8');

        // Объединение critical.css и critical-page.css
        const combinedCss = criticalAppCss + '\n' + processedPurgedCss;

        // Финальная обработка объединенного CSS без минификации
        const finalResult = await processCssContentWithoutMinification(combinedCss);

        // Запись немодифицированного результата в critical-page.css
        await fs.writeFile('web/css/critical-page.css', finalResult);
        console.log('Файл critical-page.css успешно обновлен и очищен от дубликатов');

        // Создание минифицированной версии
        const minifiedResult = await processCssContentWithMinification(finalResult);

        // Запись минифицированного результата в critical-page.min.css
        await fs.writeFile('web/css/critical-page.min.css', minifiedResult);
        console.log('Файл critical-page.min.css успешно создан и минифицирован');

        // Минификация main.css
        const minifiedMainCss = await processCssContentWithMinification(mainCss);

        // Запись минифицированного main.css
        await fs.writeFile('web/css/main.min.css', minifiedMainCss);
        console.log('Файл main.min.css успешно создан и минифицирован');


    } catch (error) {
        console.error('Произошла ошибка:', error);
    }
}

processCss();
