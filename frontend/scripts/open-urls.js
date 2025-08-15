import {readFile} from 'fs/promises';
import open from 'open';

// Функция для задержки должна быть объявлена здесь, выше использования
const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Define paths relative to the script location
const projectRoot = join(__dirname, '..');
const filePath = join(projectRoot, 'urls.txt');


try {
    const data = await readFile(filePath, 'utf8');
    const urls = data.split('\n').filter(url => url.trim() !== '');

    for (const url of urls) {
        console.log(`Открываем: ${url.trim()}`); // Выводим информацию о текущем URL
        await open(url.trim());                  // Открываем URL в браузере
        await delay(3000);                       // Задержка 3000 мс между открытиями
    }
} catch (err) {
    console.error('Ошибка при чтении файла:', err);
}
