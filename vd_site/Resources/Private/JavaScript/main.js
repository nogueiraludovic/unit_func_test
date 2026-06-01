'use strict'

import Cookies from 'js-cookie'

document.addEventListener('DOMContentLoaded', () => {
  const alertCookie = document.querySelector('.alert-cookie')

  alertCookie.addEventListener('close.bs.alert', () => {
    Cookies.set('vd_cookie_warning', 1, { expires: 30, sameSite: 'none', secure: true })
  })

  if (parseInt(Cookies.get('vd_cookie_warning')) !== 1) {
    alertCookie.classList.remove('d-none')
  }

  if (Vaud && Vaud.prestationLoginUrl) {
    loadLogin().then(() => {})
  }

  document.querySelector('a[href="#main"]').addEventListener('click', (event) => {
    scrollToTop(event)
  })

  document.querySelector('#btn-go-to-top').addEventListener('click', (event) => {
    scrollToTop(event)
  })

  const irfaqForms = document.querySelectorAll('.irfaq-search-form form')

  irfaqForms.forEach((irfaqForm) => {
    irfaqForm.addEventListener('submit', (event) => {
      event.preventDefault()

      return false
    })
  })

  const faqForms = document.querySelectorAll('.search-form-faq')

  if (faqForms.length > 0) {
    faqForms.forEach((faqForm) => {
      searchFaq(faqForm)

      faqForm.addEventListener('input', (input) => {
        searchFaq(input.target)
      })
    })
  }

  if (window.screen.width < 576) {
    if (document.querySelector('.js-closeMenuOnMobile__button') && document.querySelector('.js-closeMenuOnMobile__container')) {
      document.querySelector('.js-closeMenuOnMobile__button').classList.add('collapsed')
      document.querySelector('.js-closeMenuOnMobile__button').setAttribute('aria-expanded', 'false')
      document.querySelector('.js-closeMenuOnMobile__container').classList.remove('show')
    }
  }

  document.addEventListener('click', event => hideConnectionCollapse(event, 'login-form-collapse'))
  document.addEventListener('click', event => hideConnectionCollapse(event, 'logout-form-collapse'))
})

async function fetchWithTimeout(resource, options = {}) {
  const controller = new AbortController()
  const { timeout = 5000 } = options
  const id = setTimeout(() => controller.abort(), timeout)
  const response = await fetch(resource, {...options, signal: controller.signal})

  clearTimeout(id)

  return response
}

async function hideConnectionCollapse(event, collapseId) {
  const button = document.querySelector(`[data-bs-target="#${collapseId}"]`);
  const collapse = document.getElementById(collapseId);

  if (!button.contains(event.target) && collapse.classList.contains('show') && !collapse.contains(event.target)) {
    const bsCollapse = bootstrap.Collapse.getInstance(collapse);

    if (bsCollapse) {
      bsCollapse.hide();
    }
  }
}

async function loadLogin() {
  try {
    const response = await fetchWithTimeout(Vaud.prestationLoginUrl + 'sessioninfo/me', {
      credentials: 'include',
      redirect: 'error'
    })
    const data = await response.json()

    document.querySelector('#login-section').classList.add('d-none')

    const logoutLink = document.querySelector('[data-btn="logout"]')
    const logoutSection = document.querySelector('#logout-section')

    logoutSection.classList.remove('d-none')
    logoutLink.text = `${data.firstName} ${data.lastName}`
  } catch (error) {}
}

async function scrollToTop(event) {
  event.preventDefault()

  document.body.scrollTop = 0
  document.documentElement.scrollTop = 0
}

async function searchFaq(form) {
  const items = form.closest('.tx-plain-faq').querySelectorAll('.accordion-item, .irfaq-answers article, .vd-list-links li')

  if (items.length === 0) {
    return
  }

  items.forEach((item) => {
    let text = ''

    item.querySelectorAll('*[data-search-field="1"]').forEach(value => {
      text +=value.innerText.toLowerCase()
    })

    if (text.indexOf(form.value.toLowerCase()) === -1) {
      item.classList.add('d-none')
      item.setAttribute('aria-hidden', 'true')
    } else {
      item.classList.remove('d-none')
      item.removeAttribute('aria-hidden')
    }
  })
}
