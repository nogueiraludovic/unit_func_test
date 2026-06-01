'use strict'

export default class AjaxResponse {
  constructor(response) {
    this.response = response
  }

  async resolve() {
    const contentType = this.response.headers.get('Content-Type') || ''

    if (contentType.includes('application/json') === true) {
      try {
        return await this.response.json()
      } catch (error) {
        console.warn('Failed to parse JSON:', error)
        return {}
      }
    }

    return await this.response.text()
  }
}
