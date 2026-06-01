'use strict'

import AjaxRequest from './Ajax/Request.js'
import Event from './Event/Event.js'
import Tablesort from 'tablesort'

export default class VdWsProsecutor {
  constructor() {
    const plugins = document.querySelectorAll('.vd-wsprosecutor')

    if (plugins.length === 0) {
      return this
    }

    this
      .loadAction(plugins)
      .then(async () => new Event('click', event => this.paginateAction(event.target))
        .delegateTo(document, 'a[data-action="paginate"]'))
      .catch(error => console.error(error))
  }

  loadAction(plugins) {
    return new Promise(resolve => {
      for (const plugin of plugins) {
        this._initializeTableSort(plugin.dataset.container)
      }

      return resolve()
    })
  }

  paginateAction(target) {
    return this
      ._setPaginationInProgress(target)
      ._sendGetRequest(target)
  }

  _initializeTableSort(container) {
    container = document.querySelector('table[data-sortable="' + container + '"]');

    if (container === null) {
      return this
    }

    // noinspection JSUnresolvedReference
    new Tablesort(container)

    return this
  }

  _sendGetRequest(target) {
    new AjaxRequest(target.dataset.url)
      .get()
      .then(async response => await response.resolve())
      .then(async content => await this._setContent(target.dataset.container, content))
      .then(async () => await this._initializeTableSort(target.dataset.container))
      .catch(error => console.error(error))

    return this
  }

  _setContent(container, content) {
    document.querySelector('div[data-container="' + container + '"]').outerHTML = content

    return this
  }

  _setPaginationInProgress(link) {
    link.querySelector('.vd-pagination__label').innerHTML = '<i aria-hidden="true" class="fa-circle-notch fa-solid fa-spin"></i>'
    link.setAttribute('aria-disabled', 'true')

    return this
  }
}
