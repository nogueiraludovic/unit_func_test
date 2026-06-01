'use strict'

import AjaxRequest from './Ajax/Request.js'

export default class VdOjvEncheres {
  constructor() {
    this.abortController = new WeakMap()

    document.querySelectorAll('.vd-ojvencheres').forEach(container => {
      this._setupListener(container)
    })
  }

  _buildUrl(selects) {
    const params = new URLSearchParams({'tx_vdojvencheres[ajax-filter]': '1'})

    selects.forEach(select => {
      const field = String(select.dataset.field || '')

      if (field === '') {
        return
      }

      const value = String(select.value || '')

      if (value !== '') {
        params.set(`tx_vdojvencheres[${field}]`, value)
      }

      const uids = String(select.dataset.uids || '')

      if (uids !== '') {
        params.set(`tx_vdojvencheres[${field}Uids]`, uids)
      }
    })

    return '?' + params.toString()
  }

  _setLoadingState(select, isLoading) {
    select.classList.toggle('disabled', isLoading)
    select.disabled = isLoading

    isLoading === true ? select.setAttribute('aria-disabled', 'true') : select.removeAttribute('aria-disabled')
  }

  _setSelectFields(selects, data, previousValues) {
    selects.forEach(select => {
      if (select.dataset.ajax === 'disabled') {
        return
      }

      const field = String(select.dataset.field || '')
      const options = Array.isArray(data[field]) ? data[field] : []

      select.options.length = 0

      options.forEach(option => {
        select.add(new Option(option.name, String(option.uid)))
      })

      const previousValue = previousValues.get(select) || ''

      if ([...select.options].some(option => option.value === previousValue)) {
        select.value = previousValue
      } else if (select.options.length > 0) {
        select.selectedIndex = 0
      }
    })
  }

  _setupListener(container) {
    container.addEventListener('change', event => {
      if (event.target.tagName.toLowerCase() === 'select') {
        this._updateOptions(container).catch(console.error)
      }
    })
  }

  async _updateOptions(container) {
    const previousController = this.abortController.get(container)

    if (previousController instanceof AbortController) {
      previousController.abort()
    }

    const controller = new AbortController()
    const previousValues = new Map()
    const selects = container.querySelectorAll('select')

    this.abortController.set(container, controller)

    selects.forEach(select => {
      previousValues.set(select, String(select.value))

      this._setLoadingState(select, true)
    })

    try {
      const url = this._buildUrl(selects)
      const response = await new AjaxRequest(url).get({
        headers: {'Content-Type':'application/json;charset=utf-8'},
        signal: controller.signal
      })
      const data = await response.resolve()

      this._setSelectFields(selects, data, previousValues)
    } catch (error) {
      if (error instanceof DOMException && error.name === 'AbortError') {
        return
      }

      console.error('AJAX error:', error)
    } finally {
      selects.forEach(select => this._setLoadingState(select, false))
    }
  }
}
