const {dest, src, watch, series, parallel} = require('gulp');
const sass = require('gulp-sass');
const rename = require('gulp-rename');

function compilar(){
    return src("assets/scss/*.scss")
    .pipe(sass())    
    .pipe(dest("assets/css"))
}

function renombrar(){
    return src("assets/scss/*scss")
    .pipe(sass())
    .pipe(rename("estilo.css"))
    .pipe(dest("assets/css"))
}

function vigilar(){
    watch("assets/scss/*.scss", renombrar)
}

exports.default = vigilar;
exports.compilar = compilar;
exports.renombrar = renombrar;