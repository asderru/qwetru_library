import gulp from 'gulp';
import browserSync from 'browser-sync';
import concat from 'gulp-concat';
import plumber from 'gulp-plumber';
import rigger from 'gulp-rigger';
import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify';
import * as sass from 'sass';
import gulpSass from 'gulp-sass';

// Настройка компилятора Sass
const sassCompiler = gulpSass(sass);

const {series, parallel, watch} = gulp;
const server = browserSync.create();

export function serve(done) {
    server.init({
        server: {
            baseDir: "./web"
        },
        tunnel: false
    });
    done();
}

// Copy index.html
export function html() {
    return gulp
        .src("./src/index.html")
        .pipe(rigger())
        .pipe(gulp.dest("./web"))
        .pipe(server.stream());
}
async function images2webp() {
    const imagemin = (await import('imagemin')).default;
    const imageminWebp = (await import('imagemin-webp')).default;
    const {glob} = await import('glob'); // Исправлено: деструктуризация glob
    const path = await import('path');
    const fs = await import('fs');

    // Найти все изображения в подпапках
    const files = await glob('./src/img/**/*.{jpg,jpeg,png}');

    for (const file of files) {
        // Получаем относительный путь от src/img
        const relativePath = path.relative('./src/img', file);
        const outputDir = path.join('./web/img', path.dirname(relativePath));

        // Создаем директорию если она не существует
        await fs.promises.mkdir(outputDir, {recursive: true});

        // Устанавливаем разрешения для директории
        await fs.promises.chmod(outputDir, 0o775);

        // Обрабатываем файл
        const result = await imagemin([file], {
            destination: outputDir,
            plugins: [
                imageminWebp({quality: 90})
            ]
        });

        // Устанавливаем разрешения для созданного файла
        const fileName = path.parse(file).name + '.webp';
        const outputFilePath = path.join(outputDir, fileName);

        try {
            await fs.promises.chmod(outputFilePath, 0o775);
        } catch (err) {
            console.log(`Could not set permissions for ${outputFilePath}:`, err.message);
        }
    }

    console.log('Images optimized:', files.length, 'files processed');
}

// Дополнительная функция для установки разрешений всех существующих файлов
export async function fixPermissions() {
    const glob = (await import('glob')).glob;
    const fs = await import('fs');

    // Устанавливаем разрешения для всех файлов в ./web/img/
    const allFiles = await glob('./web/img/**/*');

    for (const file of allFiles) {
        try {
            const stats = await fs.promises.stat(file);
            if (stats.isFile()) {
                await fs.promises.chmod(file, 0o775);
            } else if (stats.isDirectory()) {
                await fs.promises.chmod(file, 0o775);
            }
        } catch (err) {
            console.log(`Could not set permissions for ${file}:`, err.message);
        }
    }

    console.log('Permissions fixed for all files in ./web/img/');
}

// Задача для CSS
export async function styles() {
    const autoprefixer = (await import('gulp-autoprefixer')).default;
    const cleanCSS = (await import('gulp-clean-css')).default; // Импортируем gulp-clean-css
    const gulpSassInstance = gulpSass(sass);

    return gulp
        .src("./src/scss/main.scss")
        .pipe(plumber())
        .pipe(gulpSassInstance({outputStyle: "expanded"}))
        .pipe(concat("main.css"))
        .pipe(sourcemaps.init())
        .pipe(autoprefixer({
            overrideBrowserslist: ["last 2 versions"],
            cascade: false
        }))
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest("./web/css/"))
        .pipe(cleanCSS()) // Минификация CSS
        .pipe(concat("main.min.css")) // Переименование в main.min.css
        .pipe(gulp.dest("./web/css/")) // Сохранение минифицированного файла
        .pipe(server.stream());
}


// JS task
export function scripts() {
    return gulp
        .src("./src/js/main.js")
        .pipe(plumber()) // для отслеживания ошибок
        .pipe(rigger()) // импортируем все указанные файлы в main.js
        .pipe(sourcemaps.init()) //инициализируем sourcemap
        .pipe(uglify()) // минимизируем js
        .pipe(sourcemaps.write("./")) //  записываем sourcemap
        .pipe(gulp.dest("./web/js/")) // положим готовый файл
        .pipe(server.stream()); // Используем server.stream() для инъекции с
}

// Watch files
export function watchFiles() {
    server.init({
        server: {
            baseDir: "./web"
        },
        tunnel: false
    });
    gulp.watch("./src/views/**/*", html);
    gulp.watch("./src/views/img/**/*", images2webp);
    gulp.watch("./src/scss/**/*", styles);
    gulp.watch("./src/js/down/*", scripts);
    gulp.watch("./web/index.html", server.reload);
    gulp.watch("./web/main.css", server.reload);
}

// Delete files
export async function clean() {
    const del = (await import('del')).deleteAsync;
    return del([
        "./web/css/main.css",
        "./web/css/main.min.css",
        "./web/css/main.css.map",
        "./web/js*"
    ]);
}

// Сборка и запуск задач по умолчанию
export const build = series(clean, parallel(styles, html, images2webp, scripts));
export default series(build, watchFiles);
