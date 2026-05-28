'use strict'

import AjaxRequest from '../../../../../private/typo3conf/ext/vd_frontend/Resources/Private/JavaScript/Classes/Ajax/Request.js'

document.addEventListener('VdFrontendContentLoaded', () => {
  const containers = document.querySelectorAll('.vd-frontend')

  if (containers.length === 0) {
    return this
  }

  for (const container of containers) {
    const contentUid = container.querySelector('input[name="tx_vdfrontend_recordlist[demand][contentUid]"]').value
    const axis = container.querySelector('select[data-field="axis"]')
    const dicastery = container.querySelector('select[data-field="dicastery"]')
    const pecc = container.querySelector('select[data-field="pecc"]')
    const themes = container.querySelector('select[data-field="theme"]')
    const values = {
      'axis': axis === null ? '' : axis.value,
      'dicastery': dicastery === null ? '' : dicastery.value,
      'pecc': pecc === null ? '' : pecc.value,
      'theme': themes === null ? '' : themes.value
    }

    if (axis !== null) {
      axis.addEventListener('change', (event) => {
        updateAll(contentUid, axis, dicastery, pecc, themes, values)
      })
    }

    if (dicastery !== null) {
      dicastery.addEventListener('change', (event) => {
        updateAll(contentUid, axis, dicastery, pecc, themes, values)
      })
    }

    if (pecc !== null) {
      pecc.addEventListener('change', (event) => {
        updateAll(contentUid, axis, dicastery, pecc, themes, values)
      })
    }

    if (themes !== null) {
      themes.addEventListener('change', (event) => {
        updateAll(contentUid, axis, dicastery, pecc, themes, values)
      })
    }
  }

  function updateAll (contentUid, axis, dicastery, pecc, themes, values) {
    updateOptions(contentUid, axis, values)
    updateOptions(contentUid, dicastery, values)
    updateOptions(contentUid, pecc, values)
    updateOptions(contentUid, themes, values)
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

    const axis = String(values.axis || '')
    const dicastery = String(values.dicastery || '')
    const pecc = String(values.pecc || '')
    const theme = String(values.theme || '')

    params.set('tx_climatepolicy[contentUid]', contentUid)

    if (axis !== '') {
      params.set('tx_climatepolicy[axis]', axis)
    }

    if (dicastery !== '') {
      params.set('tx_climatepolicy[dicastery]', dicastery)
    }

    if (pecc !== '') {
      params.set('tx_climatepolicy[pecc]', pecc)
    }

    switch (String(target.dataset.field || '')) {
      case 'axis':
        params.set('tx_climatepolicy[table]', 'tx_vdclimatepolicy_domain_model_axis')
        break
      case 'dicastery':
        params.set('tx_climatepolicy[table]', 'tx_vdclimatepolicy_domain_model_dicastery')
        break
      case 'pecc':
        params.set('tx_climatepolicy[table]', 'tx_vdclimatepolicy_domain_model_pecc')
        break
      case 'theme':
        params.set('tx_climatepolicy[table]', 'tx_vdclimatepolicy_domain_model_theme')
        break
    }

    if (theme !== '') {
      params.set('tx_climatepolicy[theme]', theme)
    }

    return '/climate-policy/ajax?' + params.toString()
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
