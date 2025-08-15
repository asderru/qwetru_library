import {readFile, writeFile} from 'fs/promises';
import {dirname, join} from 'path';
import {fileURLToPath} from 'url';
import postcss from 'postcss';
import CleanCSS from 'clean-css';
import discardDuplicates from 'postcss-discard-duplicates';

// Get the current file's directory
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

// Define paths relative to the script location
const projectRoot = join(__dirname, '..');
const inputPath = join(projectRoot, 'web/css/critical-chapter.css');
const outputPath = join(projectRoot, 'web/css/critical-chapter.min.css');

async function optimizeCriticalCSS() {
    try {
        // Read the input CSS file
        const css = await readFile(inputPath, 'utf8');

        // Process with PostCSS to remove duplicates
        const result = await postcss([
            discardDuplicates()
        ]).process(css, {
            from: inputPath,
            to: outputPath
        });

        // Initialize CleanCSS
        const minifier = new CleanCSS({
            level: {
                1: {
                    specialComments: 0 // Remove all comments
                },
                2: {
                    mergeSemantically: true,
                    restructureRules: true
                }
            }
        });

        // Минифицируем CSS
        const minified = minifier.minify(result.css);

        // Write the optimized CSS to output file
        await writeFile(outputPath, minified.styles);

        console.log('CSS optimization completed successfully!');
        console.log(`Original size: ${css.length} bytes`);
        console.log(`Optimized size: ${minified.styles.length} bytes`);
        console.log(`Reduction: ${((1 - minified.styles.length / css.length) * 100).toFixed(2)}%`);

    } catch (error) {
        console.error('Error during CSS optimization:', error);
        process.exit(1);
    }
}

optimizeCriticalCSS();
