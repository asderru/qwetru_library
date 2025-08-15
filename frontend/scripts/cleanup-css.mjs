import fs from 'fs';
import path from 'path';

const cssInput = path.join(process.cwd(), 'web', 'css', 'main.css');
const cssOutput = path.join(process.cwd(), 'web', 'css', 'main-cleared.css');

function processMediaQueries(css) {
    const mediaQueries = new Map();
    let depth = 0;
    let currentQuery = '';
    let currentContent = '';
    let inMediaBlock = false;

    for (let i = 0; i < css.length; i++) {
        const char = css[i];

        if (char === '@' && css.slice(i, i + 6) === '@media') {
            if (depth === 0) {
                let j = i;
                while (j < css.length && css[j] !== '{') j++;
                currentQuery = css.slice(i, j).trim(); // Сохраняем с @
                i = j - 1;
                continue;
            }
        }

        if (char === '{') {
            depth++;
            if (depth === 1 && currentQuery) {
                inMediaBlock = true;
                continue;
            }
        }

        if (char === '}') {
            depth--;
            if (depth === 0 && inMediaBlock) {
                inMediaBlock = false;
                if (!mediaQueries.has(currentQuery)) {
                    mediaQueries.set(currentQuery, '');
                }
                mediaQueries.set(currentQuery, mediaQueries.get(currentQuery) + currentContent + '}');
                currentContent = '';
                currentQuery = '';
                continue;
            }
        }

        if (inMediaBlock && depth >= 1) {
            currentContent += char;
        }
    }

    return mediaQueries;
}

function cleanupBraces(css) {
    return css.replace(/}(\s*})+/g, '}')
        .replace(/}\s*@media/g, '}\n\n@media');
}

function processCSSValue(value) {
    if (value.includes('url(') || value.includes('--bs-')) {
        return value.trim();
    }
    return value.trim();
}

function mergeDuplicateSelectors(css) {
    // Обработка медиа-запросов
    const mediaQueries = processMediaQueries(css);

    // Удаляем медиа-запросы из основного CSS
    let regularCss = css;
    mediaQueries.forEach((content, query) => {
        regularCss = regularCss.replace(query + '{' + content, '');
    });

    // Обработка обычных правил
    const regularRules = {};
    const rulePattern = /([^@{]+){([^}]+)}/g;
    let match;

    while ((match = rulePattern.exec(regularCss)) !== null) {
        const selector = match[1].trim();
        let styles = match[2].trim();

        if (!regularRules[selector]) {
            regularRules[selector] = new Map();
        }

        // Обработка URL и переменных
        const urlPattern = /([-\w]+)\s*:\s*([^;]+url\([^)]+\)[^;]*);/g;
        let urlMatch;

        while ((urlMatch = urlPattern.exec(styles)) !== null) {
            const [fullMatch, prop, value] = urlMatch;
            regularRules[selector].set(prop.trim(), value.trim());
            styles = styles.replace(fullMatch, '');
        }

        styles.split(';')
            .filter(prop => prop.trim())
            .forEach(prop => {
                const colonIndex = prop.indexOf(':');
                if (colonIndex > -1) {
                    const key = prop.slice(0, colonIndex).trim();
                    const value = prop.slice(colonIndex + 1).trim();
                    if (key && value) {
                        regularRules[selector].set(key, processCSSValue(value));
                    }
                }
            });
    }

    // Формируем результат
    let result = '';

    // Добавляем обычные стили
    if (Object.keys(regularRules).length > 0) {
        Object.entries(regularRules).forEach(([selector, properties]) => {
            result += `${selector} {\n`;
            properties.forEach((value, prop) => {
                result += `    ${prop}: ${value};\n`;
            });
            result += '}\n\n';
        });
    }

    // Добавляем медиа-запросы
    mediaQueries.forEach((content, query) => {
        // Проверяем наличие @ в начале query
        if (!query.startsWith('@media')) {
            query = '@media' + query.slice(5);
        }
        result += `${query} {\n${content}\n\n`;
    });

    return cleanupBraces(result);
}

try {
    const cssContent = fs.readFileSync(cssInput, 'utf8');
    const cleanedCss = mergeDuplicateSelectors(cssContent);
    fs.writeFileSync(cssOutput, cleanedCss);
    console.log('CSS успешно обработан и сохранен в', cssOutput);
} catch (error) {
    console.error('Ошибка при обработке CSS:', error);
}
