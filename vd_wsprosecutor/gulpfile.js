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
  scripts: {
    destination: './Resources/Public/JavaScript',
    source: './Resources/Private/JavaScript/**/*.js'
  },
  styles: {
    destination: './Resources/Public/Css',
    source: './Resources/Private/Scss/**/*.scss'
  }
}
const PROD = process.env.NODE_ENV === 'production'

const clean = () => {
  return deleteAsync([
    PATHS.scripts.destination,
    PATHS.styles.destination
  ])
}

const scripts = () => {
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
    .pipe(concat('vd-wsprosecutor.min.js'))
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
    .pipe(rename({suffix: '.min'}))
    .pipe(condition(PROD === false, sourcemaps.write('', {})))
    .pipe(dest(PATHS.styles.destination))
}

const watcher = () => {
  watch(PATHS.scripts.source, scripts)
  watch(PATHS.styles.source, styles)
}

export const build = series(clean, parallel(scripts, styles))
export const development = series(parallel(scripts, styles), watcher)
