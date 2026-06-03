import { src, dest, watch, series, parallel } from "gulp";
import gulpSass from "gulp-sass";
import * as sass from "sass";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import browserSync from "browser-sync";
import imagemin from "gulp-imagemin";
import groupCssMediaQueries from "gulp-group-css-media-queries";
import sourcemaps from "gulp-sourcemaps";
import newer from "gulp-newer";
import webp from "gulp-webp";

const compileSass = gulpSass(sass);
const browserSyncInstance = browserSync.create();

// パス設定
const paths = {
  scss: "src/assets/scss/**/*.scss",
  js: "src/assets/js/**/*.js",
  images: "src/assets/images/**/*.{jpg,png,gif}",
  dist: {
    css: "assets/css",
    js: "assets/js",
    images: "assets/images",
  },
};

// **🔹 Sassのコンパイル**
export function sassTask() {
  return src(paths.scss)
    .pipe(sourcemaps.init())
    .pipe(compileSass().on("error", compileSass.logError))
    .pipe(postcss([autoprefixer()]))
    .pipe(groupCssMediaQueries())
    .pipe(sourcemaps.write("."))
    .pipe(dest(paths.dist.css))
    .pipe(browserSyncInstance.stream({ match: "**/*.css" })); // CSS変更を即座に反映
}

// **🔹 JavaScriptの処理**
export function jsTask() {
  return src(paths.js)
    .pipe(dest(paths.dist.js))
    .pipe(browserSyncInstance.stream());
}

// **🔹 画像の最適化**
export function optimizeImages() {
  return src(paths.images, { encoding: false })
    .pipe(newer(paths.dist.images))
    .pipe(
      imagemin([
        imagemin.mozjpeg({ quality: 95, progressive: true }),
        imagemin.optipng({ optimizationLevel: 2 }),
      ])
    )
    .on("error", console.error)
    .pipe(dest(paths.dist.images));
}

// **🔹 WebP 変換**
export function convertToWebP() {
  return src(paths.images, { encoding: false })
    .pipe(newer(paths.dist.images))
    .pipe(
      webp({
        lossless: true,
        quality: 100,
        method: 0,
      })
    )
    .pipe(dest(paths.dist.images));
}

// **🔹 ブラウザ同期の初期化**
export function browserSyncInit(done) {
  browserSyncInstance.init({
    proxy: "localhost:8888",
    port: 3000,
    notify: false, // 通知を非表示にして高速化
    open: false, // 自動でブラウザを開かない
    ghostMode: false, // スクロールやクリックの同期を無効化
    files: [
      // CSSはsassTaskのstreamでインジェクトするため、ここでは不要
      "**/*.php", // PHPファイルの変更を監視
      "**/*.html", // HTMLファイルの変更を監視
    ],
  });
  done();
}

// **🔹 ファイル変更の監視**
export function watchFiles() {
  watch(paths.scss, sassTask);
  watch(paths.js, jsTask);
  watch(paths.images, parallel(optimizeImages, convertToWebP));
}

// **📌 デフォルトタスク**
export default series(
  parallel(sassTask, jsTask, optimizeImages, convertToWebP),
  browserSyncInit,
  watchFiles
);
