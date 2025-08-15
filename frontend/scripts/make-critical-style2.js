import fs from 'fs';
import path from 'path';

// Пути к файлам
const classesInput = path.join(process.cwd(), 'scripts', 'all-classes.txt');
const idsInput = path.join(process.cwd(), 'scripts', 'all-ids.txt');
const cssInput = path.join(process.cwd(), 'web', 'css', 'main.css');
const cssOutput = path.join(process.cwd(), 'web', 'css', 'critical.css');

// Базовые HTML-теги, которые всегда должны быть включены
const basicTags = [
    // Корневые элементы
    'html', 'body',

    // Метаданные
    'head', 'meta', 'title', 'base', 'link',

    // Секционные элементы
    'header', 'footer', 'main', 'article', 'aside', 'nav', 'section',
    'h1', 'h2', 'h3', 'h4', 'h5', 'h6',

    // Группировка контента
    'div', 'p', 'hr', 'pre', 'blockquote', 'figure', 'figcaption',
    'ul', 'ol', 'li', 'dl', 'dt', 'dd',

    // Текстовая семантика
    'a', 'span', 'em', 'strong', 'i', 'b', 'u', 's', 'small',


    // Мультимедиа
    'img', 'audio', 'video', 'source', 'track', 'map', 'area',
    'svg', 'picture', 'canvas',

    // Таблицы
    'table', 'caption', 'colgroup', 'col', 'thead', 'tbody', 'tfoot',
    'tr', 'td', 'th',

];

// Функция для чтения файла и возврата массива строк
function readFileToArray(filePath) {
    const content = fs.readFileSync(filePath, 'utf-8');
    return content.split('\n').filter(line => line.trim() !== '');
}

// Функция для фильтрации CSS
function filterCSS(cssContent, classes, ids) {
    // Регулярные выражения для поиска базовых тегов, классов и ID
    const basicTagsRegex = new RegExp(`^\\s*(${basicTags.join('|')})\\b`, 'gm');
    const classRegex = new RegExp(`\\.(${classes.join('|')})(?![a-zA-Z0-9_-])`, 'g');
    const idRegex = new RegExp(`#(${ids.join('|')})(?![a-zA-Z0-9_-])`, 'g');

    // Разделяем CSS на отдельные правила
    const rules = cssContent.split(/\}\s*/g);

    // Фильтруем правила
    const filteredRules = rules
        .map(rule => {
            const selectorPart = rule.split(/\{/)[0]; // Часть с селектором
            const bodyPart = rule.split(/\{/)[1]; // Часть с телом правила

            // Проверяем, содержит ли селектор базовые теги, классы или ID
            if (
                selectorPart.match(basicTagsRegex) || // Базовые теги
                selectorPart.match(classRegex) || // Классы
                selectorPart.match(idRegex) // ID
            ) {
                return `${selectorPart} {${bodyPart}}`;
            }
            return ''; // Пропускаем правило, если оно не подходит
        })
        .filter(rule => rule.trim() !== ''); // Убираем пустые правила

    return filteredRules.join('\n');
}

async function main() {
    try {
        // Читаем файлы с классами и ID
        const classes = readFileToArray(classesInput);
        const ids = readFileToArray(idsInput);

        // Читаем исходный CSS файл
        const cssContent = fs.readFileSync(cssInput, 'utf-8');

        // Фильтруем CSS
        const criticalCSS = filterCSS(cssContent, classes, ids);

        // Сохраняем результат в новый файл
        fs.writeFileSync(cssOutput, criticalCSS);

        console.log('Critical CSS successfully generated!');
        console.log(`Output saved to: ${cssOutput}`);
    } catch (error) {
        console.error('Error during critical CSS generation:', error);
    }
}

// Запуск скрипта
main();
