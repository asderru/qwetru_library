import {promises as fs} from 'fs';
import {dirname, join} from 'path';
import axios from 'axios';
import {fileURLToPath} from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename); // Fixed: two underscores instead of asterisks
// Using relative paths based on script location
const INPUT_FILE = join(__dirname, 'urls.txt');
const OUTPUT_DIR = join(__dirname, '../src/views/layouts');
const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

async function extractContentAndSave() {
    try {
        // Чтение файла с URL-адресами
        const fileContent = await fs.readFile(INPUT_FILE, 'utf8');
        const urls = fileContent.split('\n').filter(url => url.trim());
        // Создание выходной директории, если она не существует
        await fs.mkdir(OUTPUT_DIR, {recursive: true});
        for (const url of urls) {
            try {
                // Получение HTML-контента
                const response = await axios.get(url);
                const html = response.data;
                // Извлечение контента между тегами
                const startTag = '<!-- content starts -->';
                const endTag = '<!-- content ends -->';
                const startIndex = html.indexOf(startTag) + startTag.length;
                const endIndex = html.indexOf(endTag);
                if (startIndex === -1 || endIndex === -1) {
                    console.error(`Теги контента не найдены для URL: ${url}`);
                    continue;
                }
                const content = html.slice(startIndex, endIndex).trim();
                // Создание имени файла из последней части URL
                const urlObj = new URL(url);
                const urlParts = urlObj.pathname.split('/').filter(Boolean);
                const fileName = `${urlParts[urlParts.length - 1] || 'index'}.html`;
                const outputPath = join(OUTPUT_DIR, fileName);
                // Удаление старого файла, если он существует
                try {
                    await fs.unlink(outputPath);
                } catch (error) {
                    if (error.code !== 'ENOENT') throw error;
                }
                // Сохранение нового файла
                await fs.writeFile(outputPath, content);
                await delay(3000);                       // Задержка 3000 мс между открытиями
                console.log(`Успешно обработан URL: ${url} -> ${fileName}`);
            } catch (error) {
                console.error(`Ошибка при обработке URL ${url}:`, error.message);
            }
        }
        console.log('Обработка завершена');
    } catch (error) {
        console.error('Произошла ошибка:', error.message);
    }
}

extractContentAndSave();
