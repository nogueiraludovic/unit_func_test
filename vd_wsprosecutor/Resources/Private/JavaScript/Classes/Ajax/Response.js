'use strict'

export default class AjaxResponse {
  constructor (response) {
    this.response = response
  }

  async resolve () {
    const contentType = this.response.headers.get('Content-Type') ?? ''

    if (contentType.startsWith('application/json')) {
      return await this.response.json();
    }

    return await this.response.text()
  }
}
