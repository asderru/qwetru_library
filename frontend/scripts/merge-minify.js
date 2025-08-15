import fs from 'fs/promises';
import CleanCSS from 'clean-css';

// Get the current file's directory
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const projectRoot = join(__dirname, '..');

const criticalStraight = join(projectRoot, 'web/css/critical-chapter.css');
const criticalMin = join(projectRoot, 'web/css/critical-chapter.min.css');
const mainStyle = join(projectRoot, 'web/css/critical-chapter.min.css');

async function mergeAndMinify() {
    try {
        // Чтение содержимого файлов
        const criticalCss = await fs.readFile(criticalStraight, 'utf8');
        const criticalMinCss = await fs.readFile(criticalMin, 'utf8');
        const mainCss = await fs.readFile(mainStyle, 'utf8');

        // Объединение содержимого critical файлов
        const combinedCss = criticalCss + '\n' + criticalMinCss;

        // Минификация critical файлов
        const minifiedCritical = new CleanCSS().minify(combinedCss);

        // Запись минифицированного результата для critical.min.css
        await fs.writeFile('web/css/critical.min.css', minifiedCritical.styles);
        console.log('Файл critical.min.css успешно обновлен');

        // Минификация main.css
        const minifiedMain = new CleanCSS().minify(mainCss);

        // Запись минифицированного результата для main.min.css
        await fs.writeFile('web/css/main.min.css', minifiedMain.styles);
        console.log('Файл main.min.css успешно создан');
    } catch (error) {
        console.error('Произошла ошибка:', error);
    }
}


mergeAndMinify();
