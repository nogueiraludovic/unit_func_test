'use strict'

import autoprefixer from 'gulp-autoprefixer'
import cleanCss from 'gulp-clean-css'
import concat from 'gulp-concat'
import condition from 'gulp-if'
// noinspection JSUnresolvedReference
import {deleteAsync} from 'del'
import gulp from 'gulp'
import plumber from 'gulp-plumber'
import rename from 'gulp-rename'
import scss from 'gulp-dart-scss'
import sourcemaps from 'gulp-sourcemaps'
import terser from 'gulp-terser'
import webpackStream from 'webpack-stream'

const {dest, parallel, series, src, watch} = gulp
const PATHS = {
  foehn: {
    destination: './Resources/Public/Foehn/dist',
    source: './node_modules/@dsivd/foehn/dist/**/*'
  },
  fontAwesome: {
    destination: './Resources/Public/Fonts',
    source: './node_modules/@fortawesome/fontawesome-free/webfonts/**/*.woff2'
  },
  icons: {
    destination: './Resources/Public/Icons',
    source: './node_modules/@fortawesome/fontawesome-free/svgs/**/*.svg'
  },
  scripts: {
    destination: './Resources/Public/JavaScript',
    source: './Resources/Private/JavaScript/**/*.js'
  },
  styles: {
    destination: './Resources/Public/Css',
    source: './Resources/Private/Scss/main.scss'
  },
  stylesBackend: {
    destination: './Resources/Public/Css/Backend',
    source: './Resources/Private/Scss/Backend/**/*.scss'
  }
}
// noinspection JSUnresolvedReference
const PROD = process.env.NODE_ENV === 'production'

const clean = () => {
  return deleteAsync([
    PATHS.foehn.destination,
    PATHS.fontAwesome.destination + '/font-awesome',
    PATHS.icons.destination + '/font-awesome',
    PATHS.scripts.destination + '/bundle.*',
    PATHS.styles.destination + '/bundle.*',
    PATHS.stylesBackend.destination
  ])
}

const copyFoehn = () => {
  return src(PATHS.foehn.source, {encoding: false}).pipe(dest(PATHS.foehn.destination + '/'))
}

const copyFontAwesome = () => {
  return src(PATHS.fontAwesome.source, {encoding: false})
    .pipe(dest(PATHS.fontAwesome.destination + '/font-awesome'))
    .on('end', async () => {
      await deleteAsync('./Resources/Public/Fonts/font-awesome/fa-v4compatibility.woff2')
    })
}

const copyIcons = () => {
  return src(PATHS.icons.source, {encoding: false})
    .pipe(dest(PATHS.icons.destination + '/font-awesome'))
}

const scripts = () => {
  // noinspection JSUnresolvedReference
  return src(PATHS.scripts.source)
    .pipe(plumber())
    .pipe(webpackStream({
      devtool: PROD === false ? 'inline-cheap-source-map' : false,
      mode: process.env.NODE_ENV,
      module: {
        rules: [{
          exclude: /node_modules/,
          test: /\.js$/
        }]
      },
      output: {hashFunction: 'sha256'},
      performance: {hints: false}
    }))
    .pipe(condition(PROD === false, sourcemaps.init({})))
    .pipe(terser())
    .pipe(concat('bundle.min.js'))
    .pipe(condition(PROD === false, sourcemaps.write('', {})))
    .pipe(dest(PATHS.scripts.destination))
}

const styles = () => {
  return src(PATHS.styles.source)
    .pipe(plumber())
    .pipe(condition(PROD === false, sourcemaps.init({})))
    .pipe(scss({
      quietDeps: true,
      silenceDeprecations: ['import', 'legacy-js-api']
    }))
    .pipe(cleanCss())
    .pipe(autoprefixer({cascade: false}))
    .pipe(rename({
      basename: 'bundle',
      suffix: '.min'
    }))
    .pipe(condition(PROD === false, sourcemaps.write('', {})))
    .pipe(dest(PATHS.styles.destination))
}

const stylesBackend = () => {
  return src(PATHS.stylesBackend.source)
    .pipe(plumber())
    .pipe(condition(PROD === false, sourcemaps.init({})))
    .pipe(scss({
      quietDeps: true,
      silenceDeprecations: ['import', 'legacy-js-api']
    }))
    .pipe(autoprefixer({cascade: false}))
    .pipe(condition(PROD === false, sourcemaps.write('', {})))
    .pipe(dest(PATHS.stylesBackend.destination))
}

const watcher = () => {
  watch(PATHS.scripts.source, scripts)
  watch(PATHS.styles.source, styles)
  watch(PATHS.styles.source, stylesBackend)
}

export const build = series(clean, copyFoehn, copyFontAwesome, copyIcons, parallel(scripts, styles, stylesBackend))
export const development = series(copyFoehn, copyFontAwesome, copyIcons, parallel(scripts, styles, stylesBackend), watcher)
