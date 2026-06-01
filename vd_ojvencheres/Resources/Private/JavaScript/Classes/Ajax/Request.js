'use strict'

import AjaxResponse from './Response.js'

export default class AjaxRequest {
  constructor(url) {
    this.defaultOptions = {
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    }
    this.url = url
  }

  async get(options = {}) {
    return this._sendRequest({
      method: 'GET',
      ...options
    })
  }

  async post(data, options = {}) {
    return this._sendRequest({
      body: data,
      cache: 'no-cache',
      method: 'POST',
      ...options}
    )
  }

  _resolveUrl() {
    if (this.url.startsWith('?') === true) {
      return new URL(window.location.pathname + this.url, window.location.origin).toString()
    }

    return new URL(this.url, window.location.origin).toString()
  }

  async _sendRequest(options = {}) {
    const url = this._resolveUrl()

    try {
      const response = await fetch(url, {
        ...this.defaultOptions,
        ...options
      })

      return new AjaxResponse(response)
    } catch (error) {
      console.error(`Network error on ${url}`, error)
      throw error
    }
  }
}
