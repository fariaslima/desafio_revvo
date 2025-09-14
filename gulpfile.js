// Importando as dependências
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');  // Para minificar o CSS, se necessário
const sourcemaps = require('gulp-sourcemaps'); // Para gerar os sourcemaps

// Caminhos dos arquivos
const paths = {
  scss: 'public/assets/scss/**/*.scss', // Todos os arquivos SCSS dentro de public/scss
  css: 'public/assets/css',             // Destino da compilação CSS
};

// Tarefa para compilar SCSS para CSS
gulp.task('sass', () => {
  return gulp.src('public/assets/scss/main.scss') // O arquivo principal de entrada
    .pipe(sourcemaps.init())  // Inicia a geração do sourcemap
    .pipe(sass().on('error', sass.logError)) // Compila SCSS para CSS
    .pipe(cleanCSS())         // Minifica o CSS, caso queira
    .pipe(sourcemaps.write('.')) // Gera o sourcemap no mesmo diretório
    .pipe(gulp.dest(paths.css)); // Salva o CSS compilado na pasta public/css
});

// Tarefa para observar mudanças nos arquivos SCSS
gulp.task('watch', () => {
  gulp.watch(paths.scss, gulp.series('sass')); // Observa os arquivos SCSS e executa a tarefa 'sass' quando houver mudanças
});

// Tarefa padrão
gulp.task('default', gulp.series('sass', 'watch')); // Compila o SCSS e começa a observar as mudanças
