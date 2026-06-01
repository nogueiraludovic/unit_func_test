'use strict'

import concat from 'gulp-concat'
import condition from 'gulp-if'
import { deleteAsync } from 'del'
import gulp from 'gulp'
import plumber from 'gulp-plumber'
import sourcemaps from 'gulp-sourcemaps'
import terser from 'gulp-terser'
import webpack from 'webpack-stream'

const { dest, parallel, series, src, watch } = gulp
const PATHS = {
  scripts: {
    destination: './Resources/Public/JavaScript',
    source: './Resources/Private/JavaScript/**/*.js'
  }
}
const PROD = process.env.NODE_ENV === 'production'

const clean = () => {
  return deleteAsync([
    PATHS.scripts.destination
  ])
}

const scripts = () => {
  return src(PATHS.scripts.source)
    .pipe(plumber())
    .pipe(webpack({
      devtool: PROD === false ? 'inline-cheap-source-map' : false,
      mode: process.env.NODE_ENV,
      module: {
        rules: [
          {
            exclude: /node_modules/,
            test: /\.js$/
          }
        ]
      },
      output: {
        hashFunction: 'sha256'
      },
      performance: {
        hints: false
      }
    }))
    .pipe(condition(PROD === false, sourcemaps.init({})))
    .pipe(terser())
    .pipe(concat('vd-directory.min.js'))
    .pipe(condition(PROD === false, sourcemaps.write('', {})))
    .pipe(dest(PATHS.scripts.destination))
}

const watcher = () => {
  watch(PATHS.scripts.source, scripts)
}

export const build = series(clean, scripts)
export const development = series(scripts, watcher)
