'use strict'

import AjaxResponse from './Response.js'

export default class AjaxRequest {
  constructor (url) {
    this.defaultOptions = { credentials: 'same-origin' }
    this.url = url
  }

  async get (options = {}) {
    return new AjaxResponse(await this._send({ ...{ method: 'GET' }, ...options }))
  }

  async post (data, options = {}) {
    return new AjaxResponse(await this._send({ ...{ body: data, cache: 'no-cache', method: 'POST' }, ...options }))
  }

  _buildUrl () {
    let url = this.url

    if (url.charAt(0) === '?') {
      return new URL(window.location.origin + window.location.pathname + url, window.location.origin).toString()
    }

    return new URL(url, window.location.origin).toString()
  }

  async _send (options = {}) {
    const response = await fetch(this._buildUrl(), { ...this.defaultOptions, ...options })

    if (response.ok === false) {
      throw new AjaxResponse(response)
    }

    return response
  }
}
