// Используем await для динамического импорта
const fs = await import('fs');
const path = await import('path');

// Пути к файлам
const viewsDir = path.default.join(process.cwd(), 'src', 'views', 'layouts');
const classesOutput = path.default.join(process.cwd(), 'scripts', 'all-classes.txt');
const idsOutput = path.default.join(process.cwd(), 'scripts', 'all-ids.txt');

// Создаем множества для хранения уникальных значений
const classSet = new Set();
const idSet = new Set();

// Функция для рекурсивного чтения директории
function readDirectory(directory) {
    const files = fs.default.readdirSync(directory);
    files.forEach(file => {
        const fullPath = path.default.join(directory, file);
        const stat = fs.default.statSync(fullPath);
        if (stat.isDirectory()) {
            readDirectory(fullPath);
        } else {
            // Читаем только файлы с расширениями .html, .vue, .jsx, .tsx
            if (/\.(html|vue|jsx|tsx)$/.test(file)) {
                const content = fs.default.readFileSync(fullPath, 'utf-8');

                // Ищем все class атрибуты с одинарными или двойными кавычками
                const classRegex = /class\s*=\s*(['"])(.*?)\1/g;
                let match;

                while ((match = classRegex.exec(content)) !== null) {
                    const classes = match[2].trim().split(/\s+/);
                    classes.forEach(cls => {
                        if (cls) classSet.add(cls);
                    });
                }

                // Ищем все id атрибуты с одинарными или двойными кавычками
                const idRegex = /id\s*=\s*(['"])(.*?)\1/g;
                while ((match = idRegex.exec(content)) !== null) {
                    const id = match[2].trim();
                    if (id) idSet.add(id);
                }
            }
        }
    });
}

// Функция для чтения существующих данных из файла
function readExistingData(filePath) {
    if (fs.default.existsSync(filePath)) {
        const data = fs.default.readFileSync(filePath, 'utf-8');
        return new Set(data.split('\n').filter(line => line.trim() !== ''));
    }
    return new Set();
}

// Функция для записи данных в файл с сортировкой и удалением дубликатов
function writeDataToFile(filePath, newData) {
    const existingData = readExistingData(filePath);
    const combinedData = new Set([...existingData, ...newData]);
    const sortedData = Array.from(combinedData).sort().join('\n');
    fs.default.writeFileSync(filePath, sortedData);
}

// Создаем директорию scripts, если она не существует
const scriptsDir = path.default.join(process.cwd(), 'scripts');
if (!fs.default.existsSync(scriptsDir)) {
    fs.default.mkdirSync(scriptsDir, {recursive: true});
}

try {
    // Начинаем сканирование
    readDirectory(viewsDir);

    // Записываем результаты в файлы
    writeDataToFile(classesOutput, classSet);
    writeDataToFile(idsOutput, idSet);

    console.log('Extraction completed successfully!');
    console.log(`Found ${classSet.size} unique classes and ${idSet.size} unique IDs`);
    console.log(`Classes saved to: ${classesOutput}`);
    console.log(`IDs saved to: ${idsOutput}`);
} catch (error) {
    console.error('Error during extraction:', error);
}
