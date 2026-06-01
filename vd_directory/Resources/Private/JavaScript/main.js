'use strict'

import AjaxRequest from '../../../../../private/typo3conf/ext/vd_frontend/Resources/Private/JavaScript/Classes/Ajax/Request.js'

document.addEventListener('VdFrontendContentLoaded', () => {
  const containers = document.querySelectorAll('.vd-frontend')

  if (containers.length === 0) {
    return this
  }

  for (const container of containers) {
    const contentUid = container.querySelector('input[name="tx_vdfrontend_recordlist[demand][contentUid]"]').value
    const sectors = container.querySelector('select[data-field="sector"]')
    const services = container.querySelector('select[data-field="service"]')
    const themes = container.querySelector('select[data-field="theme"]')

    if (sectors !== null) {
      sectors.addEventListener('change', (event) => {
        updateOptions(contentUid, sectors, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
        updateOptions(contentUid, services, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
        updateOptions(contentUid, themes, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
      })
    }

    if (services !== null) {
      services.addEventListener('change', (event) => {
        updateOptions(contentUid, sectors, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
        updateOptions(contentUid, services, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
        updateOptions(contentUid, themes, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
      })
    }

    if (themes !== null) {
      themes.addEventListener('change', (event) => {
        updateOptions(contentUid, sectors, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
        updateOptions(contentUid, services, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
        updateOptions(contentUid, themes, { 'sector': sectors.value, 'service': services.value, 'theme': themes.value })
      })
    }
  }

  function updateOptions (contentUid, target, values = {}) {
    if (target === null) {
      return
    }

    _disableField(target)

    new AjaxRequest(_buildUrl(contentUid, target, values))
      .get({ headers: { 'Content-Type': 'application/json;charset=utf-8' } })
      .then(async response => await response.resolve())
      .then(async options => await _setOptions(options, target))
      .then(async () => await _enableField(target))
      .catch(error => console.error(error))
  }

  function _buildUrl (contentUid, target, values = {}) {
    const params = new URLSearchParams()

    const sector = String(values.sector || '')
    const service = String(values.service || '')
    const theme = String(values.theme || '')

    params.set('tx_directory[contentUid]', contentUid)

    if (sector !== '') {
      params.set('tx_directory[sector]', sector)
    }

    if (service !== '') {
      params.set('tx_directory[service]', service)
    }

    switch (String(target.dataset.field || '')) {
      case 'sector':
        params.set('tx_directory[table]', 'tx_vddirectory_domain_model_sector')
        break
      case 'service':
        params.set('tx_directory[table]', 'tx_vddirectory_domain_model_service')
        break
      case 'theme':
        params.set('tx_directory[table]', 'tx_vddirectory_domain_model_theme')
        break
    }

    if (theme !== '') {
      params.set('tx_directory[theme]', theme)
    }

    return '/directory/ajax?' + params.toString()
  }

  function _disableField (field) {
    field.classList.add('disabled')
    field.disabled = true
    field.setAttribute('aria-disabled', 'true')
  }

  function _enableField (field) {
    field.classList.remove('disabled')
    field.disabled = false
    field.removeAttribute('aria-disabled')

    return this
  }

  function _setOptions (options, target) {
    let selectedValue = target.value

    target.length = 0

    for (const option of options) {
      let newOption = document.createElement('option')
      let optionValue = option.uid.toString()

      if (optionValue === selectedValue) {
        newOption.selected = 'true'
      }

      newOption.text = option.name
      newOption.value = optionValue

      target.add(newOption)
    }

    return this
  }
})
